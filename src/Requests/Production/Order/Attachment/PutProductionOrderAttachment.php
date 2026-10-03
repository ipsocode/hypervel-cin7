<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/attachment`, body is a `ProductionOrderAttachmentPutData`; the response is
 * the saved attachment.
 *
 * @extends WriteRequest<ProductionOrderAttachmentData>
 */
final class PutProductionOrderAttachment extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/order/attachment';
    }

    public function createDtoFromResponse(Response $response): ProductionOrderAttachmentData
    {
        return ProductionOrderAttachmentData::from($response->json())->setResponse($response);
    }
}
