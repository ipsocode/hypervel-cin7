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
        // What made the module fail: a list pull's error, or a document pull that wrote nothing.
        public ?Throwable $error = null,
        // Documents whose read failed and were recorded on their list rows, while others were written.
        public int $failedDocuments = 0,
        // The first of those errors.
        public ?Throwable $documentError = null,
    ) {
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }
}
