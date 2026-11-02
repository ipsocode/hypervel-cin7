<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Console;

use Hypervel\Console\Command;
use Hypervel\Support\Carbon;
use Hypervel\Support\Str;
use InvalidArgumentException;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\PullResult;
use Ipsocode\Cin7\Sync\SyncConfig;
use Ipsocode\Cin7\Sync\Synchroniser;

/**
 * Runs a pull in the foreground, for a first fill or a repair by hand; the schedule queues
 * the same pull as a job.
 *
 * @see src/docs/sync.md
 */
class SyncCommand extends Command
{
    protected ?string $signature = 'cin7:sync
        {module?* : The modules to pull, or * for every one (default: cin7.sync.modules)}
        {--full : Ignore the look-back, pull every record, and remove what Cin7 no longer returns}
        {--retry-failed : Let the documents of these modules that stopped being tried be tried again, then pull}
        {--status : Show what the table holds for each module, and pull nothing}';

    protected string $description = 'Copy Cin7 records into the local payload table';

    public function handle(Synchroniser $synchroniser, Cin7Connector $cin7): int
    {
        if (! SyncConfig::enabled()) {
            $this->error('The Cin7 sync is off. Set CIN7_SYNC=true (cin7.sync.enabled) to use it.');

            return self::FAILURE;
        }

        try {
            $modules = $this->modules();
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($this->option('status')) {
            $this->status($modules, $cin7->accountId());

            return self::SUCCESS;
        }

        if ($this->option('retry-failed')) {
            $this->release($modules, $cin7->accountId());
        }

        $results = $synchroniser->pullMany($modules, (bool) $this->option('full'));

        $this->table(
            ['Module', 'Pull', 'Read', 'Written', 'Unchanged', 'Deleted', 'Result'],
            array_map(static fn (PullResult $result): array => [
                $result->module->value,
                $result->full ? 'full' : 'incremental',
                $result->read,
                $result->written,
                $result->unchanged,
                $result->deleted,
                match (true) {
                    $result->skipped => 'skipped: another pull holds its lock',
                    $result->failed() => 'failed: ' . $result->error?->getMessage(),
                    $result->failedDocuments > 0 => sprintf('ok, %d %s failed: %s', $result->failedDocuments, Str::plural('document', $result->failedDocuments), $result->documentError?->getMessage()),
                    default => 'ok',
                },
            ], $results),
        );

        return array_filter($results, static fn (PullResult $result): bool => $result->failed()) === []
            ? self::SUCCESS
            : self::FAILURE;
    }

    /**
     * @return list<Module>
     */
    private function modules(): array
    {
        $names = (array) $this->argument('module');

        return $names === [] ? SyncConfig::modules() : SyncConfig::resolve($names);
    }

    /**
     * Zero the failure counters of the list rows behind each document module among the modules.
     *
     * @param list<Module> $modules
     */
    private function release(array $modules, string $accountId): void
    {
        foreach ($modules as $module) {
            if ($module->source() === null) {
                continue;
            }

            $released = Payload::forModule($module->source(), $accountId)->toBase()
                ->where('failures', '>', 0)
                ->update(['failures' => 0, 'failed_at' => null, 'last_error' => null]);

            $this->info(sprintf('%s: %d %s released.', $module->value, $released, Str::plural('document', $released)));
        }
    }

    /**
     * @param list<Module> $modules
     */
    private function status(array $modules, string $accountId): void
    {
        $this->table(
            ['Module', 'Rows', 'Parked documents', 'Last synced', 'Newest change in Cin7'],
            array_map(static function (Module $module) use ($accountId): array {
                $rows = Payload::forModule($module, $accountId);
                // PostgreSQL drops trailing zeros from fractional seconds, so spell the time out.
                $newest = $rows->max('cin7_modified_at');

                return [
                    $module->value,
                    $rows->count(),
                    $module->source() === null ? '-' : Payload::forModule($module->source(), $accountId)
                        ->where('failures', '>=', SyncConfig::maxFailures())
                        ->count(),
                    (string) $rows->max('synced_at') ?: 'never',
                    $newest === null ? '-' : Carbon::parse($newest)->format('Y-m-d H:i:s.v'),
                ];
            }, $modules),
        );
    }
}
