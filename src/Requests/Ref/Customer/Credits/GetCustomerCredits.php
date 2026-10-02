<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Credits;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/customer/credits` — the list envelope is keyed `CustomerCredits` and carries no
 * `Total`, so a page is the last one when it holds fewer items than the limit sent.
 *
 * @extends ListRequest<list<CustomerCreditData>>
 */
final class GetCustomerCredits extends ListRequest
{
    protected string $listKey = 'CustomerCredits';

    public function resolveEndpoint(): string
    {
        return 'ref/customer/credits';
    }

    /**
     * @return list<CustomerCreditData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): CustomerCreditData => CustomerCreditData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
