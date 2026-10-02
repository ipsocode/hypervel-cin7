<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Requests\MoneyTask\DeleteMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\GetMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PostMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PutMoneyTask;

/**
 * The Money Task, on `moneyOperation`: named after the model it serves, as the reference's Money
 * Task group names it, not after the path. It has no list action; V2 lists money tasks at
 * `moneyTaskList`, which is `Cin7Connector::moneyTaskList()`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class MoneyTaskResource extends BaseResource
{
    /**
     * One money task.
     */
    public function get(
        string $taskId,
    ): Response {
        return $this->connector->send(new GetMoneyTask($taskId));
    }

    /**
     * @param array<string, mixed>|MoneyTaskData $body
     */
    public function post(array|MoneyTaskData $body): Response
    {
        return $this->connector->send(new PostMoneyTask($body));
    }

    /**
     * @param array<string, mixed>|MoneyTaskData $body
     */
    public function put(array|MoneyTaskData $body): Response
    {
        return $this->connector->send(new PutMoneyTask($body));
    }

    /**
     * Void the money task (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(
        string $id,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteMoneyTask($id, $void));
    }
}
