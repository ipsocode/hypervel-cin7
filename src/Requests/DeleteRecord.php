<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\PageDefaults;

/**
 * Delete a record by GUID.
 *
 * Two upstream quirks are preserved: the GUID goes in the query string under
 * `deleteGuidKey()` — which is `ID` even for the `sale/*` endpoints that find
 * by `SaleID` — and a delete carries the page/limit defaults just like a read.
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
final class DeleteRecord extends Cin7Request
{
    protected Method $method = Method::DELETE;

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
        $parameters = $this->parameters;
        $parameters[$this->endpoint->deleteGuidKey()] = $this->guid;

        return PageDefaults::apply($parameters);
    }
}
