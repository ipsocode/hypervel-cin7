<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\PageDefaults;

/**
 * Find one record by GUID.
 *
 * Cin7 takes the GUID as a query parameter — not a path segment — keyed by
 * the endpoint's GUID field (`ID` for most, `SaleID` for the `sale/*` ones).
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
        // The GUID is assigned last, so it wins over a caller-supplied value
        // under the same key — the behavior the legacy client had.
        $parameters = $this->parameters;
        $parameters[$this->endpoint->guidKey()] = $this->guid;

        return PageDefaults::apply($parameters);
    }
}
