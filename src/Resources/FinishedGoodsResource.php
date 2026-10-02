<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsPostData;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsPutData;
use Ipsocode\Cin7\Requests\FinishedGoods\DeleteFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\GetFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\PostFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\PutFinishedGoods;
use Ipsocode\Cin7\Resources\FinishedGoods\OrderResource;
use Ipsocode\Cin7\Resources\FinishedGoods\PickResource;

/**
 * `finishedGoods`, a finished goods task.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class FinishedGoodsResource extends BaseResource
{
    /**
     * One finished goods task, with its lines.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetFinishedGoods($taskId));
    }

    /**
     * @param array<string, mixed>|FinishedGoodsPostData $body
     */
    public function post(array|FinishedGoodsPostData $body): Response
    {
        return $this->connector->send(new PostFinishedGoods($body));
    }

    /**
     * @param array<string, mixed>|FinishedGoodsPutData $body
     */
    public function put(array|FinishedGoodsPutData $body): Response
    {
        return $this->connector->send(new PutFinishedGoods($body));
    }

    /**
     * Void the task (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteFinishedGoods($id, $void));
    }

    /**
     * The `finishedGoods/order` resource, a task's order.
     */
    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }

    /**
     * The `finishedGoods/pick` resource, a task's pick.
     */
    public function pick(): PickResource
    {
        return new PickResource($this->connector);
    }
}
