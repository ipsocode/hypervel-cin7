<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\FinishedGoods;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickData;
use Ipsocode\Cin7\Requests\FinishedGoods\Pick\GetFinishedGoodsPick;
use Ipsocode\Cin7\Requests\FinishedGoods\Pick\PostFinishedGoodsPick;

/**
 * `finishedGoods/pick`, a finished goods pick.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PickResource extends BaseResource
{
    /**
     * A finished goods task's pick.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetFinishedGoodsPick($taskId));
    }

    /**
     * @param array<string, mixed>|FinishedGoodsPickData $body
     */
    public function post(array|FinishedGoodsPickData $body): Response
    {
        return $this->connector->send(new PostFinishedGoodsPick($body));
    }
}
