<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Void;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderMessageData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/void`, body is a `ProductionOrderVoidPostData`; the response is the
 * message.
 *
 * @extends WriteRequest<ProductionOrderMessageData>
 */
final class PostProductionOrderVoid extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/order/void';
    }

    public function createDtoFromResponse(Response $response): ProductionOrderMessageData
    {
        return ProductionOrderMessageData::from($response->json())->setResponse($response);
    }
}
