<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Release;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/release`, body is a `ProductionOrderReleasePostData`; the response is the
 * production order.
 *
 * @extends WriteRequest<ProductionOrdersData>
 */
final class PostProductionOrderRelease extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/order/release';
    }

    public function createDtoFromResponse(Response $response): ProductionOrdersData
    {
        return ProductionOrdersData::from($response->json())->setResponse($response);
    }
}
