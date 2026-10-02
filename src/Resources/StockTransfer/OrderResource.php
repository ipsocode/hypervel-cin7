<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\StockTransfer;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderPostData;
use Ipsocode\Cin7\Requests\StockTransfer\Order\GetStockTransferOrder;
use Ipsocode\Cin7\Requests\StockTransfer\Order\PostStockTransferOrder;

/**
 * `stockTransfer/order`, a stock transfer order.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * A stock transfer's order.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetStockTransferOrder($taskId));
    }

    /**
     * @param array<string, mixed>|StockTransferOrderPostData $body
     */
    public function post(array|StockTransferOrderPostData $body): Response
    {
        return $this->connector->send(new PostStockTransferOrder($body));
    }
}
