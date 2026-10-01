<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Exceptions\MethodNotAllowedException;

/**
 * Base for every Cin7 request: gates the verb against the endpoint and sets the 503 retry policy.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Request<TDto>
 */
abstract class Cin7Request extends Request
{
    use AlwaysThrowOnErrors;

    public function __construct(protected readonly Endpoint $endpoint)
    {
        // Subclasses declare $method as a property default and never assign it in their
        // constructor: PHP sets property defaults before any constructor body runs.
        $allowed = [Method::GET, ...$endpoint->writeMethods()];

        if (! in_array($this->method(), $allowed, true)) {
            throw new MethodNotAllowedException(sprintf(
                'Method [%s] is not allowed on the [%s] endpoint.',
                $this->method()->value,
                $endpoint->value,
            ));
        }

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

    public function resolveEndpoint(): string
    {
        return $this->endpoint->path();
    }

    public function endpoint(): Endpoint
    {
        return $this->endpoint;
    }
}
