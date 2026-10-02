<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentPostData;
use Ipsocode\Cin7\Requests\Sale\Attachment\DeleteSaleAttachment;
use Ipsocode\Cin7\Requests\Sale\Attachment\GetSaleAttachment;
use Ipsocode\Cin7\Requests\Sale\Attachment\PostSaleAttachment;

/**
 * `sale/attachment`, a sale's attachments.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttachmentResource extends BaseResource
{
    /**
     * A sale's attachments.
     */
    public function get(string $saleId): Response
    {
        return $this->connector->send(new GetSaleAttachment($saleId));
    }

    /**
     * Attach a file to a sale, as base64 `Content` or a `FileDownloadUrl`.
     *
     * @param array<string, mixed>|SaleAttachmentPostData $body
     */
    public function post(array|SaleAttachmentPostData $body): Response
    {
        return $this->connector->send(new PostSaleAttachment($body));
    }

    /**
     * Delete one attachment.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteSaleAttachment($id));
    }
}
