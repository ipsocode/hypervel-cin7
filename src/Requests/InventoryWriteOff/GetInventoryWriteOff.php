<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\InventoryWriteOff;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = InventoryWriteOffData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
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
}
