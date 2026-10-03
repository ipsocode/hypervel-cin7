<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/attachment`, body is a `SaleAttachmentPostData`; the response is the sale's
 * attachments.
 *
 * @extends WriteRequest<SaleAttachmentsData>
 */
final class PostSaleAttachment extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/attachment';
    }

    public function createDtoFromResponse(Response $response): SaleAttachmentsData
    {
        return SaleAttachmentsData::from($response->json())->setResponse($response);
    }
}
