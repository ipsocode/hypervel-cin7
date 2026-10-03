<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\InventoryWriteOff;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE inventoryWriteOff?ID&Void`, voids or undoes a inventory write-off; the response is the inventory write-off.
 *
 * @extends Cin7Request<InventoryWriteOffData>
 */
final class DeleteInventoryWriteOff extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
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
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }

    public function createDtoFromResponse(Response $response): InventoryWriteOffData
    {
        return InventoryWriteOffData::from($response->json())->setResponse($response);
    }
}
