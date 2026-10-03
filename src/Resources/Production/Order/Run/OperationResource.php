<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production\Order\Run;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationCompletePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationResumePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationStartPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationSuspendPutData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Complete\PutProductionOrderRunOperationComplete;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Resume\PutProductionOrderRunOperationResume;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Start\PutProductionOrderRunOperationStart;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Suspend\PutProductionOrderRunOperationSuspend;

/**
 * `production/order/run/operation/start`, the operations of a run.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OperationResource extends BaseResource
{
    /**
     * @param array<string, mixed>|ProductionRunOperationStartPutData $body
     */
    public function start(array|ProductionRunOperationStartPutData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunOperationStart($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunOperationSuspendPutData $body
     */
    public function suspend(array|ProductionRunOperationSuspendPutData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunOperationSuspend($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunOperationResumePutData $body
     */
    public function resume(array|ProductionRunOperationResumePutData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunOperationResume($body));
    }

    /**
     * @param array<string, mixed>|ProductionRunOperationCompletePutData $body
     */
    public function complete(array|ProductionRunOperationCompletePutData $body): Response
    {
        return $this->connector->send(new PutProductionOrderRunOperationComplete($body));
    }
}
