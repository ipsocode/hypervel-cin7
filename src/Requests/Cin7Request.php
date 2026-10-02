<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Base for every Cin7 request: sets the 503 retry policy.
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
            // FatalRequestException (DNS failure, refused connection, timeout) is not retried.
            when: fn (FatalRequestException|RequestException $exception): bool => $exception instanceof RequestException
                && $exception->status() === 503,
            throw: true,
        );
    }

    /**
     * Map boolean values the way Cin7 expects them on the wire: `true`/`false`, not `1`/empty.
     *
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    protected function queryValues(array $values): array
    {
        return array_map(
            static fn (mixed $value): mixed => is_bool($value) ? ($value ? 'true' : 'false') : $value,
            $values,
        );
    }
}
