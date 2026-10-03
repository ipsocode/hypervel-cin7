<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\FinishedGoods;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderData;
use Ipsocode\Cin7\Requests\FinishedGoods\Order\GetFinishedGoodsOrder;
use Ipsocode\Cin7\Requests\FinishedGoods\Order\PostFinishedGoodsOrder;

/**
 * `finishedGoods/order`, a finished goods order.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * A finished goods task's order.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetFinishedGoodsOrder($taskId));
    }

    /**
     * @param array<string, mixed>|FinishedGoodsOrderData $body
     */
    public function post(array|FinishedGoodsOrderData $body): Response
    {
        return $this->connector->send(new PostFinishedGoodsOrder($body));
    }
}
