<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAuthorisePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderPostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderPutData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderReleasePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderUndoPostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderVoidPostData;
use Ipsocode\Cin7\Requests\Production\Order\Authorise\PostProductionOrderAuthorise;
use Ipsocode\Cin7\Requests\Production\Order\GetProductionOrder;
use Ipsocode\Cin7\Requests\Production\Order\PostProductionOrder;
use Ipsocode\Cin7\Requests\Production\Order\PutProductionOrder;
use Ipsocode\Cin7\Requests\Production\Order\ReferenceData\GetProductionOrderReferenceData;
use Ipsocode\Cin7\Requests\Production\Order\Release\PostProductionOrderRelease;
use Ipsocode\Cin7\Requests\Production\Order\Undo\PostProductionOrderUndo;
use Ipsocode\Cin7\Requests\Production\Order\Void\PostProductionOrderVoid;
use Ipsocode\Cin7\Resources\Production\Order\AttachmentResource;
use Ipsocode\Cin7\Resources\Production\Order\RunResource;

/**
 * `production/order`, the order resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * A production order, answered under `ProductionOrders`.
     *
     * @param null|bool $returnAttachmentsContent return the attachments' content
     */
    public function get(string $productionOrderId, ?bool $returnAttachmentsContent = null): Response
    {
        return $this->connector->send(new GetProductionOrder($productionOrderId, $returnAttachmentsContent));
    }

    /**
     * @param array<string, mixed>|ProductionOrderPostData $body
     * @param bool $recalculateDates perform the capacity calculation, answering a warning if it fails
     */
    public function post(array|ProductionOrderPostData $body, ?bool $recalculateDates = null): Response
    {
        return $this->connector->send(new PostProductionOrder($body, $recalculateDates));
    }

    /**
     * @param array<string, mixed>|ProductionOrderPutData $body
     * @param bool $allowRecalculateDates perform the capacity calculation, answering a warning if it fails
     * @param bool $allowRecalculateCyclesAndQuantities recalculate the operations' cycles and quantities when the quantity changes
     */
    public function put(array|ProductionOrderPutData $body, ?bool $allowRecalculateDates = null, ?bool $allowRecalculateCyclesAndQuantities = null): Response
    {
        return $this->connector->send(new PutProductionOrder($body, $allowRecalculateDates, $allowRecalculateCyclesAndQuantities));
    }

    /**
     * @param array<string, mixed>|ProductionOrderAuthorisePostData $body
     */
    public function authorise(array|ProductionOrderAuthorisePostData $body): Response
    {
        return $this->connector->send(new PostProductionOrderAuthorise($body));
    }

    /**
     * @param array<string, mixed>|ProductionOrderReleasePostData $body
     */
    public function release(array|ProductionOrderReleasePostData $body): Response
    {
        return $this->connector->send(new PostProductionOrderRelease($body));
    }

    /**
     * @param array<string, mixed>|ProductionOrderUndoPostData $body
     */
    public function undo(array|ProductionOrderUndoPostData $body): Response
    {
        return $this->connector->send(new PostProductionOrderUndo($body));
    }

    /**
     * @param array<string, mixed>|ProductionOrderVoidPostData $body
     */
    public function void(array|ProductionOrderVoidPostData $body): Response
    {
        return $this->connector->send(new PostProductionOrderVoid($body));
    }

    /**
     * The reference data a production order uses.
     */
    public function referenceData(): Response
    {
        return $this->connector->send(new GetProductionOrderReferenceData);
    }

    /**
     * The `production/order/attachment` resource, the attachments of a production order.
     */
    public function attachment(): AttachmentResource
    {
        return new AttachmentResource($this->connector);
    }

    /**
     * The `production/order/run` resource, the runs of a production order.
     */
    public function run(): RunResource
    {
        return new RunResource($this->connector);
    }
}
