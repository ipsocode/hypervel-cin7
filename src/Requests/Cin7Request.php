<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Traits\Plugins\AlwaysThrowOnErrors;

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
        $this->retry(
            times: max(1, (int) (config('cin7.retry.times') ?? 4)),
            sleepMilliseconds: max(0, (int) (config('cin7.retry.delay_ms') ?? 5000)),
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
}
