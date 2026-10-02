<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET customer` — the list envelope is keyed `CustomerList`.
 *
 * @extends ListRequest<list<CustomerData>>
 */
final class GetCustomer extends ListRequest
{
    protected string $listKey = 'CustomerList';

    public function resolveEndpoint(): string
    {
        return 'customer';
    }

    /**
     * @return list<CustomerData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): CustomerData => CustomerData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
