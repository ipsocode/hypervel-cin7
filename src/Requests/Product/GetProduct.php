<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET product` — the list envelope is keyed `Products`.
 *
 * @extends ListRequest<list<ProductData>>
 */
final class GetProduct extends ListRequest
{
    protected string $listKey = 'Products';

    public function resolveEndpoint(): string
    {
        return 'product';
    }

    /**
     * @return list<ProductData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ProductData => ProductData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
