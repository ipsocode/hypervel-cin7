<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Order;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = FinishedGoodsOrderData::class;

    public function resolveEndpoint(): string
    {
        return 'finishedGoods/order';
    }
}
