<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Attachment;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SaleAttachmentsData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/attachment';
    }
}
