<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Undo;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderMessageData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/undo`, body is a `ProductionOrderUndoPostData`; the response is the
 * message.
 *
 * @extends WriteRequest<ProductionOrderMessageData>
 */
final class PostProductionOrderUndo extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/order/undo';
    }

    public function createDtoFromResponse(Response $response): ProductionOrderMessageData
    {
        return ProductionOrderMessageData::from($response->json())->setResponse($response);
    }
}
