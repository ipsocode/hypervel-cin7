<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production\Order;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentPutData;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\DeleteProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\GetProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\PostProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\PutProductionOrderAttachment;

/**
 * `production/order/attachment`, a production order's attachments.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttachmentResource extends BaseResource
{
    /**
     * @param array<string, mixed>|ProductionOrderAttachmentData $body
     * @param string $productionOrderId the production order
     */
    public function post(array|ProductionOrderAttachmentData $body, string $productionOrderId): Response
    {
        return $this->connector->send(new PostProductionOrderAttachment($body, $productionOrderId));
    }

    /**
     * @param array<string, mixed>|ProductionOrderAttachmentPutData $body
     */
    public function put(array|ProductionOrderAttachmentPutData $body): Response
    {
        return $this->connector->send(new PutProductionOrderAttachment($body));
    }

    /**
     * Deletes an attachment; the response is not documented.
     */
    public function delete(string $productionOrderAttachmentId): Response
    {
        return $this->connector->send(new DeleteProductionOrderAttachment($productionOrderAttachmentId));
    }

    /**
     * The attachments of a production order, its operations and their resources.
     *
     * @param null|bool $returnAttachmentsContent return the attachments' content
     */
    public function get(string $productionOrderId, ?bool $returnAttachmentsContent = null): Response
    {
        return $this->connector->send(new GetProductionOrderAttachment($productionOrderId, $returnAttachmentsContent));
    }
}
