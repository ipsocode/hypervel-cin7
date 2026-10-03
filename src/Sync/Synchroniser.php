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
 * @see docs/sync.md
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

            return $module->source() === null
                ? $this->pullList($module, $full, $start)
                : $this->pullDocuments($module, $full, $start);
        } finally {
            $lock->release();
        }
    }

    private function pullList(Module $module, bool $full, Carbon $start): PullResult
    {
        $empty = ! $this->rows($module)->exists();
        $full = $full || $empty || ! $module->incremental();
        $request = $module->listRequest($full ? null : $this->since($module, $start), $start);
        $paginator = $this->cin7->paginate($request)->perPageLimit(SyncConfig::limit());
        $read = $written = $unchanged = 0;

        try {
            // Driven by hand rather than foreach, so the pause falls between calls only.
            $paginator->rewind();

            while ($paginator->valid()) {
                $items = $request->mapPaginatedResponseItems($paginator->current());
                [$changed, $same] = $this->store($module, $items, $start, $full);
                $read += count($items);
                $written += $changed;
                $unchanged += $same;
                $paginator->next();

                if ($paginator->valid()) {
                    $this->pause();
                }
            }
        } catch (Throwable $exception) {
            // A first fill that failed is undone, so the next pull is a full one again.
            if ($empty) {
                $this->rows($module)->delete();
            }

            return new PullResult($module, $full, $read, $written, $unchanged, error: $exception);
        }

        // Only a complete full pull that read something can say what Cin7 no longer returns.
        $deleted = $full && $read > 0
            ? (int) $this->rows($module)->where('synced_at', '<', $start)->delete()
            : 0;

        return new PullResult($module, $full, $read, $written, $unchanged, $deleted);
    }

    /**
     * Read the documents whose list row has none yet or changed since, oldest first, up to
     * `sync.documents` a run. One that fails is left pending, and the rest are still read.
     */
    private function pullDocuments(Module $module, bool $full, Carbon $start): PullResult
    {
        $deleted = $full ? $this->deleteOrphans($module) : 0;
        $read = $written = 0;
        $error = null;

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
                $error ??= $exception;
            }
        }

        return new PullResult($module, $full, $read, $written, deleted: $deleted, error: $error);
    }

    /**
     * Since when an incremental pull asks: `sync.lookback` minutes back, or the module's last
     * sync if that is older, so a gap in the schedule is read again.
     */
    private function since(Module $module, Carbon $start): Carbon
    {
        $lookback = $start->copy()->subMinutes(SyncConfig::lookback());
        $newest = Carbon::parse((string) $this->rows($module)->max('synced_at'));

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

        $this->upsert($changed, ['payload', 'cin7_modified_at', 'synced_at', 'updated_at']);

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
            return json_decode((string) $stored, true) == $item;
        }

        return $modified !== null && Module::timestamp($stored) === $modified;
    }

    /**
     * The rows of a document module that are due a read, oldest list change first.
     *
     * @return array<int, stdClass>
     */
    private function pending(Module $module): array
    {
        $limit = SyncConfig::documents();

        if ($limit === 0) {
            return [];
        }

        return $this->pair($module, fromList: true)
            ->where(fn (QueryBuilder $query) => $query->whereNull('d.id')->orWhereColumn('l.cin7_modified_at', '>', 'd.cin7_modified_at'))
            ->orderBy('l.cin7_modified_at')
            ->orderBy('l.id')
            ->limit($limit)
            ->get(['l.cin7_id', 'l.cin7_modified_at'])
            ->all();
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
