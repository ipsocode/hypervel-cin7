<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTake\StockTakePostData;
use Ipsocode\Cin7\Data\StockTake\StockTakePutData;
use Ipsocode\Cin7\Requests\StockTake\DeleteStockTake;
use Ipsocode\Cin7\Requests\StockTake\GetStockTake;
use Ipsocode\Cin7\Requests\StockTake\PostStockTake;
use Ipsocode\Cin7\Requests\StockTake\PutStockTake;

/**
 * `stocktake`, a stock take.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockTakeResource extends BaseResource
{
    /**
     * One stock take, with its filters, the products it counts and the transactions it created.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetStockTake($taskId));
    }

    /**
     * @param array<string, mixed>|StockTakePostData $body
     */
    public function post(array|StockTakePostData $body): Response
    {
        return $this->connector->send(new PostStockTake($body));
    }

    /**
     * @param array<string, mixed>|StockTakePutData $body
     */
    public function put(array|StockTakePutData $body): Response
    {
        return $this->connector->send(new PutStockTake($body));
    }

    /**
     * Void the stock take (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteStockTake($id, $void));
    }
}
