<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/attachment`, body is a `PurchaseAttachmentPostData`; the response is the
 * purchase's attachments.
 *
 * @extends WriteRequest<PurchaseAttachmentsData>
 */
final class PostPurchaseAttachment extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'purchase/attachment';
    }

    public function createDtoFromResponse(Response $response): PurchaseAttachmentsData
    {
        return PurchaseAttachmentsData::from($response->json())->setResponse($response);
    }
}
