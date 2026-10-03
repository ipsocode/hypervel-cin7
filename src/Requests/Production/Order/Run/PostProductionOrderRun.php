<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/run`, body is a `ProductionRunPostData`; the response is the production
 * order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PostProductionOrderRun extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/order/run';
    }

    public function createDtoFromResponse(Response $response): ProductionRunsData
    {
        return ProductionRunsData::from($response->json())->setResponse($response);
    }
}
