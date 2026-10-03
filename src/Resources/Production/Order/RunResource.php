<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production\Order;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunCompletePostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunManualJournalsPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunPostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Complete\PutProductionOrderRunComplete;
use Ipsocode\Cin7\Requests\Production\Order\Run\GetProductionOrderRun;
use Ipsocode\Cin7\Requests\Production\Order\Run\ManualJournal\PutProductionOrderRunManualJournal;
use Ipsocode\Cin7\Requests\Production\Order\Run\PostProductionOrderRun;
use Ipsocode\Cin7\Requests\Production\Order\Run\PutProductionOrderRun;
use Ipsocode\Cin7\Requests\Production\Order\Run\Undo\PutProductionOrderRunUndo;
use Ipsocode\Cin7\Requests\Production\Order\Run\Void\PutProductionOrderRunVoid;
use Ipsocode\Cin7\Resources\Production\Order\Run\OperationResource;

/**
 * `production/order/run`, a production order's runs.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class RunResource extends BaseResource
{
    /**
     * @param array<string, mixed>|ProductionRunPostData $body
     */
    public function post(array|ProductionRunPostData $body): Response
    {
        return $this->connector->send(new PostProductionOrderRun($body));
    }

    /**
     * The runs of a production order.
     *
     * @param null|bool $includeAttachmentContent return the attachments' content
     */
    public function get(string $productionOrderId, ?bool $includeAttachmentContent = null): Response
    {
        return $this->connector->send(new GetProductionOrderRun($productionOrderId, $includeAttachmentContent));
    }

    /**
     * @param array<string, mixed>|ProductionRunData $body
     * @param string $productionOrderId the production order
     * @param bool $increaseOrderQuantity increase the order's quantity if the runs' quantities add up to more
     */
    public function put(array|ProductionRunData $body, string $productionOrderId, bool $increaseOrderQuantity): Response
    {
        return $this->connector->send(new PutProductionOrderRun($body, $productionOrderId, $increaseOrderQuantity));
    }

    /**
     * @param array<string, mixed>|ProductionRunCompletePostData $body
     */
    public function complete(array|ProductionRunCompletePostData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunComplete($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunUndoData $body
     */
    public function undo(array|ProductionRunUndoData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunUndo($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunUndoData $body
     */
    public function void(array|ProductionRunUndoData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunVoid($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunManualJournalsPutData $body
     * @param string $productionOrderId the production order
     */
    public function manualJournal(array|ProductionRunManualJournalsPutData $body, string $productionOrderId): Response
    {
        return $this->connector->send(new PutProductionOrderRunManualJournal($body, $productionOrderId));
    }

    /**
     * The `production/order/run/operation` resource, the operations of a run.
     */
    public function operation(): OperationResource
    {
        return new OperationResource($this->connector);
    }
}
