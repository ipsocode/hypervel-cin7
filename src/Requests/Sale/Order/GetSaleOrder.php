<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET sale/order?SaleID`, one sale's order. Optional parameters: `CombineAdditionalCharges` and `IncludeProductInfo`.
 *
 * @extends KeyedRequest<SaleOrderData>
 */
final class GetSaleOrder extends KeyedRequest
{
    protected string $idKey = 'SaleID';

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'sale/order';
    }

    public function createDtoFromResponse(Response $response): SaleOrderData
    {
        return SaleOrderData::from($response->json())->setResponse($response);
    }
}
