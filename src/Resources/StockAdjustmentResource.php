<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentPostData;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentPutData;
use Ipsocode\Cin7\Requests\StockAdjustment\DeleteStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\GetStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\PostStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\PutStockAdjustment;

/**
 * `stockadjustment`, a stock adjustment.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockAdjustmentResource extends BaseResource
{
    /**
     * One stock adjustment, with the lines it changed and the transactions they created.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetStockAdjustment($taskId));
    }

    /**
     * @param array<string, mixed>|StockAdjustmentPostData $body
     */
    public function post(array|StockAdjustmentPostData $body): Response
    {
        return $this->connector->send(new PostStockAdjustment($body));
    }

    /**
     * @param array<string, mixed>|StockAdjustmentPutData $body
     */
    public function put(array|StockAdjustmentPutData $body): Response
    {
        return $this->connector->send(new PutStockAdjustment($body));
    }

    /**
     * Void the stock adjustment (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteStockAdjustment($id, $void));
    }
}
