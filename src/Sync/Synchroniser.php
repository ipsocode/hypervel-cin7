<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use Hypervel\Database\ConnectionInterface;
use Hypervel\Database\Eloquent\Builder;
use Hypervel\Database\Query\Builder as QueryBuilder;
use Hypervel\Database\Query\JoinClause;
use Hypervel\Support\Carbon;
use Hypervel\Support\Facades\Cache;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Cin7Connector;
use stdClass;
use Throwable;

/**
 * Copies Cin7 records into the payload table, one module at a time.
 *
 * It holds only the connector, so one instance serves every coroutine; each pull's state is
 * local to the call.
 *
 * @see src/docs/sync.md
 */
final class Synchroniser
{
    /**
     * Rows per upsert or delete, so one statement stays small whatever the page size.
     */
    private const int CHUNK = 100;

    private const string DATE = 'Y-m-d H:i:s';

    public function __construct(private readonly Cin7Connector $cin7)
    {
    }

    /**
     * Pull each module in order. A module that fails does not stop the others.
     *
     * @param list<Module> $modules
     * @return list<PullResult>
     */
    public function pullMany(array $modules, bool $full = false): array
    {
        return array_map(fn (Module $module): PullResult => $this->pull($module, $full), $modules);
    }

    /**
     * Pull one module, unless another pull of it holds its lock.
     */
    public function pull(Module $module, bool $full = false): PullResult
    {
        $lock = Cache::lock('cin7:sync:' . $this->cin7->accountId() . ':' . $module->value, SyncConfig::timeout());

        if (! $lock->get()) {
            return new PullResult($module, $full, skipped: true);
        }

        try {
            // Every row a pull writes carries its start, so a full pull can tell what it saw.
            $start = Carbon::now()->startOfSecond();

            $list = $module->source();

            return $list === null
                ? $this->pullList($module, $full, $start)
                : $this->pullDocuments($module, $list, $full, $start);
        } finally {
            $lock->release();
        }
    }

    private function pullList(Module $module, bool $full, Carbon $start): PullResult
    {
        // The module's last sync, which a pull that fails must leave where it was.
        $previous = $this->rows($module)->max('synced_at');
        $empty = $previous === null;
        $full = $full || $empty || ! $module->incremental();
        $requests = [$module->listRequest($full ? null : $this->since((string) $previous, $start), $start)];

        // The bounded walk leaves out what changed after $start, which a full pull would delete as
        // gone. A second walk from $start with no bound reads those, so the deletion keeps them. A
        // first fill has nothing to delete, and the next pull reads what changed during it.
        if ($full && ! $empty && $module->bounded()) {
            $requests[] = $module->listRequest($start, null);
        }

        $read = $written = $unchanged = $walked = 0;

        try {
            foreach ($requests as $index => $request) {
                if ($index > 0) {
                    $this->pause();
                }

                $paginator = $this->cin7->paginate($request)->perPageLimit(SyncConfig::limit());

                // Driven by hand rather than foreach, so the pause falls between calls only.
                $paginator->rewind();

                while ($paginator->valid()) {
                    $items = $request->mapPaginatedResponseItems($paginator->current());
                    [$changed, $same] = $this->store($module, $items, $start, $full);
                    $read += count($items);
                    $written += $changed;
                    $unchanged += $same;

                    if ($index === 0) {
                        $walked += count($items);
                    }

                    $paginator->next();

                    if ($paginator->valid()) {
                        $this->pause();
                    }
                }
            }

            // These lists come in modified-time order with no upper bound, so a record changed
            // during the walk moves, and can push an unchanged one onto a page already read.
            // Before a row goes as gone, ask Cin7 for it by identifier: one still returned is
            // stored, which gives it this pull's start, so the deletion below keeps it.
            if ($full && $walked > 0 && $module->confirms()) {
                foreach ($this->unseen($module, $start) as $id) {
                    $find = $module->findRequest($id);

                    $this->pause();

                    $items = $find->mapPaginatedResponseItems($this->cin7->send($find));
                    [$changed, $same] = $this->store($module, $items, $start, $full);
                    $read += count($items);
                    $written += $changed;
                    $unchanged += $same;
                }
            }
        } catch (Throwable $exception) {
            // A first fill that failed is undone, so the next pull is a full one again. Otherwise
            // the rows it reached keep what it wrote but get their last sync back, so the module's
            // newest synced_at does not move and the next pull reaches back as far as this one.
            if ($empty) {
                $this->rows($module)->delete();
            } else {
                $this->rows($module)->toBase()
                    ->where('synced_at', $start->format(self::DATE))
                    ->update(['synced_at' => $previous]);
            }

            return new PullResult($module, $full, $read, $written, $unchanged, error: $exception);
        }

        // Only a complete full pull whose first walk read something can say what Cin7 no longer returns.
        $deleted = $full && $walked > 0
            ? (int) $this->rows($module)->where('synced_at', '<', $start)->delete()
            : 0;

        return new PullResult($module, $full, $read, $written, $unchanged, $deleted);
    }

    /**
     * Return the identifiers of a list module's stored rows that a pull did not reach, which a full pull
     * deletes unless Cin7 still returns them.
     *
     * @return list<string>
     */
    private function unseen(Module $module, Carbon $start): array
    {
        return array_map(
            strval(...),
            $this->rows($module)->toBase()->where('synced_at', '<', $start)->orderBy('cin7_id')->pluck('cin7_id')->all(),
        );
    }

    /**
     * Read the documents whose list row has none yet or changed since, oldest first, up to
     * `sync.documents` a run. One that fails is recorded on its list row and the rest are still
     * read; it is tried again until it has failed `sync.max_failures` times. A run that read
     * documents and wrote none says Cin7 or the credentials are failing, not the rows: the module
     * fails, and no row is blamed.
     */
    private function pullDocuments(Module $module, Module $list, bool $full, Carbon $start): PullResult
    {
        $deleted = $full ? $this->deleteOrphans($module) : 0;
        $read = $written = 0;
        $failures = $versions = [];

        foreach ($this->pending($module) as $row) {
            if ($read++ > 0) {
                $this->pause();
            }

            try {
                $request = $module->documentRequest((string) $row->cin7_id);

                $this->upsert([$this->row(
                    $module,
                    (string) $row->cin7_id,
                    (array) $this->cin7->send($request)->json(),
                    Module::timestamp($row->cin7_modified_at),
                    $start,
                )], ['payload', 'cin7_modified_at', 'synced_at', 'updated_at']);

                ++$written;
            } catch (Throwable $exception) {
                $failures[(string) $row->cin7_id] = $exception;
                $versions[(string) $row->cin7_id] = $row->cin7_modified_at;
            }
        }

        if ($failures === []) {
            return new PullResult($module, $full, $read, $written, deleted: $deleted);
        }

        if ($written === 0) {
            return new PullResult($module, $full, $read, $written, deleted: $deleted, error: reset($failures));
        }

        $this->recordFailures($list, $failures, $versions, $start);

        return new PullResult($module, $full, $read, $written, deleted: $deleted, failedDocuments: count($failures), documentError: reset($failures));
    }

    /**
     * Count a failed read on the document's list row, which exists while the document may not. A
     * row that changed since it was read is a new version, which the failure does not count against.
     *
     * @param array<string, Throwable> $failures by Cin7 identifier
     * @param array<string, null|string> $versions the list row's modified time as it was read, by Cin7 identifier
     */
    private function recordFailures(Module $list, array $failures, array $versions, Carbon $start): void
    {
        foreach ($failures as $id => $exception) {
            // On the query builder, which leaves updated_at alone.
            $query = $this->rows($list)->toBase()->where('cin7_id', (string) $id);

            $versions[$id] === null
                ? $query->whereNull('cin7_modified_at')
                : $query->where('cin7_modified_at', $versions[$id]);

            $query->increment('failures', 1, [
                'failed_at' => $start->format(self::DATE),
                'last_error' => mb_substr($exception->getMessage(), 0, 255),
            ]);
        }
    }

    /**
     * Since when an incremental pull asks: `sync.lookback` minutes back, or the module's last
     * sync if that is older, so a gap in the schedule is read again.
     */
    private function since(string $lastSync, Carbon $start): Carbon
    {
        $lookback = $start->copy()->subMinutes(SyncConfig::lookback());
        $newest = Carbon::parse($lastSync);

        return $newest->lt($lookback) ? $newest : $lookback;
    }

    /**
     * Write a page of a list module. A record that has not changed keeps its payload and
     * `updated_at`, and only its `synced_at` moves, except in a full pull, which refreshes the
     * payload as well. An incremental module's record is unchanged when its modified time has
     * not moved; a reference book's when its payload is the one stored.
     *
     * @param array<array-key, mixed> $items
     * @return array{int, int} the records written and the records unchanged
     */
    private function store(Module $module, array $items, Carbon $start, bool $full): array
    {
        $records = [];

        foreach ($items as $item) {
            $id = is_array($item) ? ($item[$module->idKey()] ?? null) : null;

            if (is_string($id) && $id !== '') {
                $records[$id] = $item;
            }
        }

        $ids = array_map(strval(...), array_keys($records));
        $compared = $module->incremental() ? 'cin7_modified_at' : 'payload';
        $stored = $ids === [] ? [] : $this->rows($module)->toBase()->whereIn('cin7_id', $ids)
            ->pluck($compared, 'cin7_id')
            ->all();

        $changed = $same = [];

        foreach ($records as $id => $item) {
            $id = (string) $id;
            $modified = $module->modifiedAt($item);
            $row = $this->row($module, $id, $item, $modified, $start);

            if (array_key_exists($id, $stored) && $this->unchanged($module, $stored[$id], $item, $modified)) {
                $same[] = $row;
            } else {
                $changed[] = $row;
            }
        }

        // A record Cin7 changed is worth another try for a document that kept failing.
        $this->upsert($changed, ['payload', 'cin7_modified_at', 'synced_at', 'updated_at', 'failures', 'failed_at', 'last_error']);

        if ($full) {
            $this->upsert($same, ['payload', 'synced_at']);
        } else {
            foreach (array_chunk(array_column($same, 'cin7_id'), self::CHUNK) as $chunk) {
                // On the query builder, which leaves updated_at alone.
                $this->rows($module)->toBase()->whereIn('cin7_id', $chunk)->update(['synced_at' => $start->format(self::DATE)]);
            }
        }

        return [count($changed), count($same)];
    }

    /**
     * Whether a stored record is the one just read: by modified time, or for a reference book,
     * whose records have none, by content. A record without a readable modified time is never
     * unchanged.
     *
     * @param array<array-key, mixed> $item
     */
    private function unchanged(Module $module, mixed $stored, array $item, ?string $modified): bool
    {
        if (! $module->incremental()) {
            return $this->canonical(json_decode((string) $stored, true)) === $this->canonical($item);
        }

        return $modified !== null && Module::timestamp($stored) === $modified;
    }

    /**
     * Return the value with the keys of every object sorted, so a payload whose keys came back in another
     * order (PostgreSQL's `jsonb` reorders them) compares equal under `===`, which unlike `==`
     * does not take `null` for `""` or `"1.0"` for `"1"`.
     */
    private function canonical(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        $value = array_map($this->canonical(...), $value);

        if (! array_is_list($value)) {
            ksort($value, SORT_STRING);
        }

        return $value;
    }

    /**
     * The rows of a document module that are due a read, oldest list change first, leaving out
     * those whose list row has failed `sync.max_failures` times.
     *
     * @return array<int, stdClass>
     */
    private function pending(Module $module): array
    {
        $limit = SyncConfig::documents();

        if ($limit === 0) {
            return [];
        }

        $due = fn (): QueryBuilder => $this->pair($module, fromList: true)
            ->where('l.failures', '<', SyncConfig::maxFailures())
            ->where(fn (QueryBuilder $query) => $query->whereNull('d.id')
                ->orWhereColumn('l.cin7_modified_at', '>', 'd.cin7_modified_at')
                // A document read while its list row had no modified time stored none, and a
                // comparison with NULL is never true.
                ->orWhere(fn (QueryBuilder $query): QueryBuilder => $query->whereNotNull('l.cin7_modified_at')->whereNull('d.cin7_modified_at')));

        // Rows with no modified time come first, read on their own because databases disagree on
        // where NULLs sort (PostgreSQL puts them last, SQLite and MySQL first).
        $rows = $due()->whereNull('l.cin7_modified_at')
            ->orderBy('l.id')
            ->limit($limit)
            ->get(['l.cin7_id', 'l.cin7_modified_at'])
            ->all();

        if (count($rows) < $limit) {
            $rows = [...$rows, ...$due()->whereNotNull('l.cin7_modified_at')
                ->orderBy('l.cin7_modified_at')
                ->orderBy('l.id')
                ->limit($limit - count($rows))
                ->get(['l.cin7_id', 'l.cin7_modified_at'])
                ->all()];
        }

        return $rows;
    }

    /**
     * Delete a document module's rows whose list row is gone. The ids are read first: MySQL will
     * not delete from a table its own subquery reads.
     */
    private function deleteOrphans(Module $module): int
    {
        $ids = $this->pair($module, fromList: false)->whereNull('l.id')->pluck('d.id')->all();

        foreach (array_chunk($ids, self::CHUNK) as $chunk) {
            Payload::query()->whereIn('id', $chunk)->delete();
        }

        return count($ids);
    }

    /**
     * A document module's rows beside their list rows, aliased `d` and `l`: every row of the
     * side named first, with the other side's row where there is one.
     */
    private function pair(Module $module, bool $fromList): QueryBuilder
    {
        [$from, $join] = $fromList ? ['l', 'd'] : ['d', 'l'];
        $modules = ['l' => $module->source()?->value, 'd' => $module->value];

        return $this->connection()->table(Payload::TABLE . " as {$from}")
            ->leftJoin(Payload::TABLE . " as {$join}", function (JoinClause $clause) use ($from, $join, $modules): void {
                $clause->on("{$join}.account_id", '=', "{$from}.account_id")
                    ->on("{$join}.cin7_id", '=', "{$from}.cin7_id")
                    ->where("{$join}.module", '=', $modules[$join]);
            })
            ->where("{$from}.account_id", $this->cin7->accountId())
            ->where("{$from}.module", $modules[$from]);
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @param list<string> $update
     */
    private function upsert(array $rows, array $update): void
    {
        foreach (array_chunk($rows, self::CHUNK) as $chunk) {
            Payload::query()->toBase()->upsert($chunk, ['account_id', 'module', 'cin7_id'], $update);
        }
    }

    /**
     * @param array<array-key, mixed> $payload
     * @return array<string, mixed>
     */
    private function row(Module $module, string $id, array $payload, ?string $modified, Carbon $start): array
    {
        $at = $start->format(self::DATE);

        return [
            'account_id' => $this->cin7->accountId(),
            'module' => $module->value,
            'cin7_id' => $id,
            'payload' => json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION),
            'cin7_modified_at' => $modified,
            'synced_at' => $at,
            'failures' => 0,
            'failed_at' => null,
            'last_error' => null,
            'created_at' => $at,
            'updated_at' => $at,
        ];
    }

    /**
     * @return Builder<Payload>
     */
    private function rows(Module $module): Builder
    {
        return Payload::forModule($module, $this->cin7->accountId());
    }

    private function connection(): ConnectionInterface
    {
        return new Payload()->getConnection();
    }

    private function pause(): void
    {
        $milliseconds = SyncConfig::pauseMs();

        if ($milliseconds > 0) {
            Sleep::for($milliseconds)->milliseconds();
        }
    }
}
