<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Void;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ProductionOrderMessageData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/void';
    }
}
