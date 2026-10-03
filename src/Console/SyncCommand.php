<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Console;

use Hypervel\Console\Command;
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
 * @see docs/sync.md
 */
class SyncCommand extends Command
{
    protected ?string $signature = 'cin7:sync
        {module?* : The modules to pull, or * for every one (default: cin7.sync.modules)}
        {--full : Ignore the look-back, pull every record, and remove what Cin7 no longer returns}
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
     * @param list<Module> $modules
     */
    private function status(array $modules, string $accountId): void
    {
        $this->table(
            ['Module', 'Rows', 'Last synced', 'Newest change in Cin7'],
            array_map(static function (Module $module) use ($accountId): array {
                $rows = Payload::forModule($module, $accountId);

                return [
                    $module->value,
                    $rows->count(),
                    (string) $rows->max('synced_at') ?: 'never',
                    (string) $rows->max('cin7_modified_at') ?: '-',
                ];
            }, $modules),
        );
    }
}
