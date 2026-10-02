<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferPostData;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferPutData;
use Ipsocode\Cin7\Requests\StockTransfer\DeleteStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\GetStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\PostStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\PutStockTransfer;
use Ipsocode\Cin7\Resources\StockTransfer\OrderResource;

/**
 * `stockTransfer`, a stock transfer.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockTransferResource extends BaseResource
{
    /**
     * One stock transfer, with its lines and its order.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetStockTransfer($taskId));
    }

    /**
     * @param array<string, mixed>|StockTransferPostData $body
     */
    public function post(array|StockTransferPostData $body): Response
    {
        return $this->connector->send(new PostStockTransfer($body));
    }

    /**
     * @param array<string, mixed>|StockTransferPutData $body
     */
    public function put(array|StockTransferPutData $body): Response
    {
        return $this->connector->send(new PutStockTransfer($body));
    }

    /**
     * Void the stock transfer (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteStockTransfer($id, $void));
    }

    /**
     * The `stockTransfer/order` resource, a stock transfer's order.
     */
    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }
}
