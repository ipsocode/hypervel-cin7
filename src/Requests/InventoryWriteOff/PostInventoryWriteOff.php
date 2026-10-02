<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\InventoryWriteOff;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST inventoryWriteOff`, body is a `InventoryWriteOffPostData`; the response is the saved inventory write-off.
 *
 * @extends WriteRequest<InventoryWriteOffData>
 */
final class PostInventoryWriteOff extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'inventoryWriteOff';
    }

    public function createDtoFromResponse(Response $response): InventoryWriteOffData
    {
        return InventoryWriteOffData::from($response->json())->setResponse($response);
    }
}
