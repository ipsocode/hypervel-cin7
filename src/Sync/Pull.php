<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use Hypervel\Bus\Queueable;
use Hypervel\Contracts\Queue\ShouldBeUnique;
use Hypervel\Contracts\Queue\ShouldQueue;
use Hypervel\Foundation\Bus\Dispatchable;
use Hypervel\Queue\InteractsWithQueue;
use Ipsocode\Cin7\Cin7Connector;

/**
 * A scheduled pull of one or more modules, as a queued job: a waiting pull is a row in the
 * queue, and one whose tries ran out is a failed job, with the error.
 *
 * @see docs/sync.md
 */
final class Pull implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public int $tries = 3;

    public int $timeout;

    /**
     * @param list<Module> $modules
     */
    public function __construct(
        public readonly array $modules,
        public readonly bool $full = false,
    ) {
        $this->onQueue(SyncConfig::queue());
        $this->timeout = SyncConfig::timeout();
    }

    /**
     * Pull the modules in order. A module that fails does not stop the others, and then fails
     * the job, so the queue tries it again.
     */
    public function handle(Synchroniser $synchroniser): void
    {
        $failed = array_values(array_filter(
            $synchroniser->pullMany($this->modules, $this->full),
            static fn (PullResult $result): bool => $result->failed(),
        ));

        if ($failed !== []) {
            throw SyncFailed::for($failed);
        }
    }

    /**
     * One pull of the same modules per account at a time.
     */
    public function uniqueId(): string
    {
        return implode(':', [
            app(Cin7Connector::class)->accountId(),
            implode(',', array_column($this->modules, 'value')),
            $this->full ? 'full' : 'incremental',
        ]);
    }

    public function uniqueFor(): int
    {
        return $this->timeout;
    }
}
