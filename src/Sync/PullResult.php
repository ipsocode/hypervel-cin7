<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use Throwable;

/**
 * What one pull of one module did.
 *
 * @see docs/sync.md
 */
final readonly class PullResult
{
    public function __construct(
        public Module $module,
        public bool $full = false,
        public int $read = 0,
        public int $written = 0,
        public int $unchanged = 0,
        public int $deleted = 0,
        // Another pull of the module held its lock, so this one did nothing.
        public bool $skipped = false,
        // The first error; a document pull reads on past one.
        public ?Throwable $error = null,
    ) {
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }
}
