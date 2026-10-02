<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockPostData;
use Ipsocode\Cin7\Requests\Purchase\Stock\GetPurchaseStock;
use Ipsocode\Cin7\Requests\Purchase\Stock\PostPurchaseStock;

/**
 * `purchase/stock`, a purchase's stock received. The reference marks it deprecated: it supports
 * only simple purchases, and an advanced purchase's stock is on `advanced-purchase/stock` and
 * `advanced-purchase/put-away`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockResource extends BaseResource
{
    /**
     * A purchase's stock received, by its `TaskID`.
     */
    public function get(
        string $taskId,
    ): Response {
        return $this->connector->send(new GetPurchaseStock($taskId));
    }

    /**
     * @param array<string, mixed>|PurchaseStockPostData $body
     */
    public function post(array|PurchaseStockPostData $body): Response
    {
        return $this->connector->send(new PostPurchaseStock($body));
    }
}
