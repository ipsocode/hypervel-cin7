<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Support;

/**
 * The random source for the retry wait's jitter. Resolved from the container when a wait is
 * computed, so a test binds a pinned one with `$this->instance(Jitter::class, ...)`.
 *
 * @see docs/requests.md
 */
class Jitter
{
    /**
     * A random whole number from 0 to `$max`, both included.
     */
    public function upTo(int $max): int
    {
        return random_int(0, $max);
    }
}
