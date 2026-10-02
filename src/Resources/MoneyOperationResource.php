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
 * `moneyOperation` has no list action; V2 lists money tasks at `moneyTaskList`, which this
 * package does not cover.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class MoneyOperationResource extends BaseResource
{
    public function get(string $taskId): Response
    {
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
     * Void the Money Task (`$void = true`), or undo a void.
     */
    public function delete(string $id, bool $void = false): Response
    {
        return $this->connector->send(new DeleteMoneyOperation($id, ['Void' => $void]));
    }
}
