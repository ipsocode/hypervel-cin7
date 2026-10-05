<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Ipsocode\Cin7\Support\Jitter;

/**
 * Base for every Cin7 request: sets the throttling retry policy.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Request<TDto>
 */
abstract class Cin7Request extends Request
{
    use AlwaysThrowOnErrors;

    public function __construct()
    {
        // Set here, not in a boot hook: PendingRequest snapshots the retry policy in its
        // constructor.
        $delay = max(0, (int) (config('cin7.retry.delay_ms') ?? 5000));
        $backoff = max(1.0, (float) (config('cin7.retry.backoff') ?? 1));
        $maxDelay = max(0, (int) (config('cin7.retry.max_delay_ms') ?? 0));
        $jitter = max(0, (int) (config('cin7.retry.jitter_ms') ?? 0));

        $this->retry(
            times: max(1, (int) (config('cin7.retry.times') ?? 4)),
            // The wait after failed attempt n: delay * backoff^(n-1), capped at max_delay_ms
            // unless that is 0, plus a random 0..jitter_ms. The defaults are a constant delay.
            sleepMilliseconds: static function (int $attempt) use ($delay, $backoff, $maxDelay, $jitter): int {
                $wait = $delay * $backoff ** ($attempt - 1);

                if ($maxDelay > 0) {
                    $wait = min($wait, $maxDelay);
                }

                return (int) min($wait, PHP_INT_MAX) + ($jitter > 0 ? app(Jitter::class)->upTo($jitter) : 0);
            },
            // Cin7 throttles with a 429 or a 503. FatalRequestException (DNS failure, refused
            // connection, timeout) is not retried.
            when: fn (FatalRequestException|RequestException $exception): bool => $exception instanceof RequestException
                && in_array($exception->status(), [429, 503], true),
            throw: true,
        );
    }

    /**
     * Map query values the way Cin7 expects them on the wire, leaving out the `null` ones: a
     * boolean as `true`/`false`, not `1`/empty, an enum as its value, and a date as ISO 8601 in
     * UTC with milliseconds, `yyyy-MM-ddTHH:mm:ss.fff`.
     *
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    protected function queryValues(array $values): array
    {
        return array_map(
            static fn (mixed $value): mixed => match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                $value instanceof BackedEnum => $value->value,
                $value instanceof DateTimeInterface => DateTimeImmutable::createFromInterface($value)
                    ->setTimezone(new DateTimeZone('UTC'))
                    ->format('Y-m-d\TH:i:s.v'),
                default => $value,
            },
            array_filter($values, static fn (mixed $value): bool => $value !== null),
        );
    }

    /**
     * The items of a list response as data objects of this class, each holding the response.
     *
     * @template TItem of Data&WithResponse
     *
     * @param class-string<TItem> $class
     * @param array<array-key, mixed> $items
     * @return list<TItem>
     */
    protected function listOf(string $class, Response $response, array $items): array
    {
        return array_map(
            static fn (array $item): Data&WithResponse => $class::from($item)->setResponse($response),
            array_values($items),
        );
    }
}
