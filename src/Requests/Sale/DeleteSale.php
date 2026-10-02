<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `DELETE sale?ID&Void`, voids or undoes a sale; the response is the Sale.
 *
 * @extends KeyedRequest<SaleData>
 */
final class DeleteSale extends KeyedRequest
{
    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    public function createDtoFromResponse(Response $response): SaleData
    {
        return SaleData::from($response->json())->setResponse($response);
    }
}
