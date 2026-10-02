<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffPostData;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffPutData;
use Ipsocode\Cin7\Requests\InventoryWriteOff\DeleteInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\GetInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\PostInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\PutInventoryWriteOff;

/**
 * `inventoryWriteOff`, a inventory write-off.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class InventoryWriteOffResource extends BaseResource
{
    /**
     * One inventory write-off, with its lines and the transactions it created.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetInventoryWriteOff($taskId));
    }

    /**
     * @param array<string, mixed>|InventoryWriteOffPostData $body
     */
    public function post(array|InventoryWriteOffPostData $body): Response
    {
        return $this->connector->send(new PostInventoryWriteOff($body));
    }

    /**
     * @param array<string, mixed>|InventoryWriteOffPutData $body
     */
    public function put(array|InventoryWriteOffPutData $body): Response
    {
        return $this->connector->send(new PutInventoryWriteOff($body));
    }

    /**
     * Void the inventory write-off (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteInventoryWriteOff($id, $void));
    }
}
