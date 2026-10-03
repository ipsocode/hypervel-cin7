<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use RuntimeException;

/**
 * One or more modules of a pull failed; the first one's error is the previous exception.
 *
 * @see docs/sync.md
 */
final class SyncFailed extends RuntimeException
{
    /**
     * @param non-empty-list<PullResult> $failed
     */
    public static function for(array $failed): self
    {
        return new self(
            'The Cin7 sync failed for ' . implode(', ', array_map(
                static fn (PullResult $result): string => sprintf('%s (%s)', $result->module->value, $result->error?->getMessage()),
                $failed,
            )) . '.',
            previous: $failed[0]->error,
        );
    }
}
