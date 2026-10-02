<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleOrderData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/order`, body is a Sale Order; the response is the saved Sale Order.
 *
 * @extends WriteRequest<SaleOrderData>
 */
final class PostSaleOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/order';
    }

    public function createDtoFromResponse(Response $response): SaleOrderData
    {
        return SaleOrderData::from($response->json())->setResponse($response);
    }
}
