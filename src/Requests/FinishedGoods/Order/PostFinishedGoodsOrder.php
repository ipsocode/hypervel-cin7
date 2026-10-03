<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST finishedGoods/order`, body is a `FinishedGoodsOrderData`; the response is the saved order.
 *
 * @extends WriteRequest<FinishedGoodsOrderData>
 */
final class PostFinishedGoodsOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'finishedGoods/order';
    }

    public function createDtoFromResponse(Response $response): FinishedGoodsOrderData
    {
        return FinishedGoodsOrderData::from($response->json())->setResponse($response);
    }
}
