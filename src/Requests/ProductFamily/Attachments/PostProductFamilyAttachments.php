<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductFamily\Attachments;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST productFamily/attachments`, body is a `ProductFamilyAttachmentPostData`; the response is the product family's
 * attachments, a bare list of `AttachmentLineData`.
 *
 * @extends WriteRequest<list<AttachmentLineData>>
 */
final class PostProductFamilyAttachments extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'productFamily/attachments';
    }

    /**
     * @return list<AttachmentLineData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(AttachmentLineData::class, $response, $response->json());
    }
}
