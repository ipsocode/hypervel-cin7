<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Requests\MoneyOperation\DeleteMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\GetMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PostMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PutMoneyOperation;

/**
 * `moneyOperation` has no list action; V2 lists money tasks at `moneyTaskList`, which is
 * `Cin7Connector::moneyTaskList()`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class MoneyOperationResource extends BaseResource
{
    /**
     * One money task.
     */
    public function get(
        string $taskId,
    ): Response {
        return $this->connector->send(new GetMoneyOperation($taskId));
    }

    /**
     * @param array<string, mixed>|MoneyTaskData $body
     */
    public function post(array|MoneyTaskData $body): Response
    {
        return $this->connector->send(new PostMoneyOperation($body));
    }

    /**
     * @param array<string, mixed>|MoneyTaskData $body
     */
    public function put(array|MoneyTaskData $body): Response
    {
        return $this->connector->send(new PutMoneyOperation($body));
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
        return $this->connector->send(new DeleteMoneyOperation($id, $void));
    }
}
