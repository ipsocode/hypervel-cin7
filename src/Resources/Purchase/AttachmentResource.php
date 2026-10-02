<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentPostData;
use Ipsocode\Cin7\Requests\Purchase\Attachment\DeletePurchaseAttachment;
use Ipsocode\Cin7\Requests\Purchase\Attachment\GetPurchaseAttachment;
use Ipsocode\Cin7\Requests\Purchase\Attachment\PostPurchaseAttachment;

/**
 * `purchase/attachment`, a purchase's attachments.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttachmentResource extends BaseResource
{
    /**
     * A purchase's attachments, by its `TaskID`.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetPurchaseAttachment($taskId));
    }

    /**
     * Attach a file to a purchase, as base64 `Content` or a `FileDownloadUrl`.
     *
     * @param array<string, mixed>|PurchaseAttachmentPostData $body
     */
    public function post(array|PurchaseAttachmentPostData $body): Response
    {
        return $this->connector->send(new PostPurchaseAttachment($body));
    }

    /**
     * Delete one attachment.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeletePurchaseAttachment($id));
    }
}
