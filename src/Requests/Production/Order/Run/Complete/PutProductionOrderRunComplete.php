<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Complete;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/complete`, body is a `ProductionRunCompletePostData`; the response is
 * the production order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PutProductionOrderRunComplete extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/complete';
    }

    public function createDtoFromResponse(Response $response): ProductionRunsData
    {
        return ProductionRunsData::from($response->json())->setResponse($response);
    }
}
