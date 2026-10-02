<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\SaleList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET saleList` — the list envelope is keyed `SaleList`.
 *
 * @extends ListRequest<list<SaleListData>>
 */
final class GetSaleList extends ListRequest
{
    protected string $listKey = 'SaleList';

    public function resolveEndpoint(): string
    {
        return 'saleList';
    }

    /**
     * @return list<SaleListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): SaleListData => SaleListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
