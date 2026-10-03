<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Void;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/void`, body is a `ProductionRunUndoData`; the response is the run,
 * with an `ErrorMessage` if it failed.
 *
 * @extends WriteRequest<ProductionRunUndoData>
 */
final class PutProductionOrderRunVoid extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/void';
    }

    public function createDtoFromResponse(Response $response): ProductionRunUndoData
    {
        return ProductionRunUndoData::from($response->json())->setResponse($response);
    }
}
