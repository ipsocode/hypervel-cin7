<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\ProductFamily;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\ProductFamily\Attachments\ProductFamilyAttachmentPostData;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\DeleteProductFamilyAttachments;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\GetProductFamilyAttachments;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\PostProductFamilyAttachments;

/**
 * `productFamily/attachments`, a product family's attachments.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttachmentsResource extends BaseResource
{
    /**
     * A product family's attachments.
     */
    public function get(string $familyId): Response
    {
        return $this->connector->send(new GetProductFamilyAttachments($familyId));
    }

    /**
     * Attach a file to a product family, as base64 `Content` or a `FileDownloadUrl`.
     *
     * @param array<string, mixed>|ProductFamilyAttachmentPostData $body
     */
    public function post(array|ProductFamilyAttachmentPostData $body): Response
    {
        return $this->connector->send(new PostProductFamilyAttachments($body));
    }

    /**
     * Delete one attachment.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteProductFamilyAttachments($id));
    }
}
