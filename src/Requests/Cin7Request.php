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
 * Base for every Cin7 request: verb gating and the 503 retry policy.
 *
 * Subclasses must declare their verb as a property default
 * (`protected Method $method = Method::POST;`) and never assign it in their
 * own constructor — PHP initializes subclass property defaults before any
 * constructor body runs, which is the only reason the gate below can read it.
 *
 * @template TDto
 * @extends Request<TDto>
 */
abstract class Cin7Request extends Request
{
    use AlwaysThrowOnErrors;

    public function __construct(protected readonly Endpoint $endpoint)
    {
        $allowed = [Method::GET, ...$endpoint->writeMethods()];

        if (! in_array($this->method(), $allowed, true)) {
            throw new MethodNotAllowedException(sprintf(
                'Method [%s] is not allowed on the [%s] endpoint.',
                $this->method()->value,
                $endpoint->value,
            ));
        }

        // The retry policy has to be on the Request before send(): PendingRequest
        // snapshots it in its constructor, before any boot hook runs. Retries are
        // 503-only and bounded — the package this replaces recursed forever.
        $this->retry(
            times: max(1, (int) (config('cin7.retry.times') ?? 4)),
            sleepMilliseconds: max(0, (int) (config('cin7.retry.delay_ms') ?? 5000)),
            // Deliberately excludes FatalRequestException: DNS failures, refused
            // connections and timeouts are not retried, matching upstream.
            when: fn (FatalRequestException|RequestException $exception): bool => $exception instanceof RequestException
                && $exception->status() === 503,
            throw: true,
        );
    }

    public function resolveEndpoint(): string
    {
        return $this->endpoint->path();
    }

    /**
     * The endpoint this request targets.
     */
    public function endpoint(): Endpoint
    {
        return $this->endpoint;
    }
}
