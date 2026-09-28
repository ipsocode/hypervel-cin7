<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Pagination\Contracts\Paginatable;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\PageDefaults;

/**
 * List records from an endpoint.
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
final class ListRecords extends Cin7Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(Endpoint $endpoint, protected readonly array $parameters = [])
    {
        parent::__construct($endpoint);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return PageDefaults::apply($this->parameters);
    }
}
