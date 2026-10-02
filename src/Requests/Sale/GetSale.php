<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET sale?ID`, one sale. `sale` has no list action; use `saleList` for that.
 *
 * Optional parameters: `CombineAdditionalCharges`, `HideInventoryMovements`,
 * `IncludeTransactions` and `CountryFormat`.
 *
 * @extends KeyedRequest<SaleData>
 */
final class GetSale extends KeyedRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    public function createDtoFromResponse(Response $response): SaleData
    {
        return SaleData::from($response->json())->setResponse($response);
    }
}
