<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\InventoryWriteOff;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET inventoryWriteOff?TaskID`, one inventory write-off, with its lines and the transactions it created.
 *
 * @extends Cin7Request<InventoryWriteOffData>
 */
final class GetInventoryWriteOff extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'inventoryWriteOff';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): InventoryWriteOffData
    {
        return InventoryWriteOffData::from($response->json())->setResponse($response);
    }
}
