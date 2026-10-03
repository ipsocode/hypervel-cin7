<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Start;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/operation/start`, body is a `ProductionRunOperationStartPutData`; the
 * response is the production order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PutProductionOrderRunOperationStart extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/operation/start';
    }

    public function createDtoFromResponse(Response $response): ProductionRunsData
    {
        return ProductionRunsData::from($response->json())->setResponse($response);
    }
}
