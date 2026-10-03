<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product\Attachments;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST product/attachments`, body is a `ProductAttachmentPostData`; the response is the product's
 * attachments, a bare list of `AttachmentLineData`.
 *
 * @extends WriteRequest<list<AttachmentLineData>>
 */
final class PostProductAttachments extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'product/attachments';
    }

    /**
     * @return list<AttachmentLineData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(AttachmentLineData::class, $response, $response->json());
    }
}
