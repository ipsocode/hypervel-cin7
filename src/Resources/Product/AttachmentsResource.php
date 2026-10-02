<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Product;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\Attachments\ProductAttachmentPostData;
use Ipsocode\Cin7\Requests\Product\Attachments\DeleteProductAttachments;
use Ipsocode\Cin7\Requests\Product\Attachments\GetProductAttachments;
use Ipsocode\Cin7\Requests\Product\Attachments\PostProductAttachments;

/**
 * `product/attachments`, a product's attachments.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttachmentsResource extends BaseResource
{
    /**
     * A product's attachments.
     */
    public function get(string $productId): Response
    {
        return $this->connector->send(new GetProductAttachments($productId));
    }

    /**
     * Attach a file to a product, as base64 `Content` or a `FileDownloadUrl`.
     *
     * @param array<string, mixed>|ProductAttachmentPostData $body
     */
    public function post(array|ProductAttachmentPostData $body): Response
    {
        return $this->connector->send(new PostProductAttachments($body));
    }

    /**
     * Delete one attachment.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteProductAttachments($id));
    }
}
