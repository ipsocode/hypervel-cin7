<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Authorise;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/authorise`, body is a `ProductionOrderAuthorisePostData`; the response is
 * the production order.
 *
 * @extends WriteRequest<ProductionOrdersData>
 */
final class PostProductionOrderAuthorise extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/order/authorise';
    }

    public function createDtoFromResponse(Response $response): ProductionOrdersData
    {
        return ProductionOrdersData::from($response->json())->setResponse($response);
    }
}
