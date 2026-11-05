<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Support;

use Hypervel\Saloon\Data\RetryPolicy;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use InvalidArgumentException;

/**
 * The throttling retry settings of one connector, read and range-checked once from `cin7.retry.*`
 * when the connection is built.
 *
 * @see src/docs/requests.md
 */
final readonly class RetrySettings
{
    public function __construct(
        public int $times,
        public int $delayMs,
        public float $backoff,
        public int $maxDelayMs,
        public int $jitterMs,
    ) {
    }

    /**
     * Read the settings from `cin7.retry.*`.
     *
     * @throws InvalidArgumentException when a value is missing, mistyped or out of range
     */
    public static function fromConfig(): self
    {
        return new self(
            ConfigRange::integer('cin7.retry.times', 1),
            ConfigRange::integer('cin7.retry.delay_ms', 0),
            ConfigRange::float('cin7.retry.backoff', 1.0),
            ConfigRange::integer('cin7.retry.max_delay_ms', 0),
            ConfigRange::integer('cin7.retry.jitter_ms', 0),
        );
    }

    /**
     * Build the policy for a 429 or a 503.
     */
    public function policy(): RetryPolicy
    {
        return new RetryPolicy(
            times: $this->times,
            // The wait after failed attempt n: delay * backoff^(n-1), capped at max_delay_ms
            // unless that is 0, plus a random 0..jitter_ms. The defaults are a constant delay.
            sleepMilliseconds: function (int $attempt): int {
                $wait = $this->delayMs * $this->backoff ** ($attempt - 1);

                if ($this->maxDelayMs > 0) {
                    $wait = min($wait, $this->maxDelayMs);
                }

                return (int) min($wait, PHP_INT_MAX) + ($this->jitterMs > 0 ? app(Jitter::class)->upTo($this->jitterMs) : 0);
            },
            // Cin7 throttles with a 429 or a 503. FatalRequestException (DNS failure, refused
            // connection, timeout) is not retried.
            when: fn (FatalRequestException|RequestException $exception): bool => $exception instanceof RequestException
                && in_array($exception->status(), [429, 503], true),
            throw: true,
        );
    }
}
