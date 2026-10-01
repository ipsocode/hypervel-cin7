<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\PageDefaults;

/**
 * Finds one record by GUID, sent as a query parameter (not a path segment) under the
 * endpoint's `guidKey()`.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
final class FindRecord extends Cin7Request
{
    protected Method $method = Method::GET;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        Endpoint $endpoint,
        protected readonly string $guid,
        protected readonly array $parameters = [],
    ) {
        parent::__construct($endpoint);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        // Assigned last so the GUID wins over a caller value under the same key.
        $parameters = $this->parameters;
        $parameters[$this->endpoint->guidKey()] = $this->guid;

        return PageDefaults::apply($parameters);
    }
}
