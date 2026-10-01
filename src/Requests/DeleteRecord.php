<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\PageDefaults;

/**
 * Deletes a record by GUID, sent in the query string under `deleteGuidKey()` along with
 * the page/limit defaults.
 *
 * That key is `ID` on every endpoint modelled here, including the `sale/*` ones that
 * find by `SaleID`.
 *
 * @see docs/requests.md
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
