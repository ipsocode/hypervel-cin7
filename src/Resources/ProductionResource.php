<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Production\FactoryCalendarResource;
use Ipsocode\Cin7\Resources\Production\OrderListResource;
use Ipsocode\Cin7\Resources\Production\OrderResource;
use Ipsocode\Cin7\Resources\Production\ProductionBomResource;
use Ipsocode\Cin7\Resources\Production\ResourceListResource;
use Ipsocode\Cin7\Resources\Production\ResourceResource;
use Ipsocode\Cin7\Resources\Production\SuspendReasonResource;
use Ipsocode\Cin7\Resources\Production\WorkCentersResource;

/**
 * `production/…`, the production resources; V2 has no action on `/production` itself.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductionResource extends BaseResource
{
    /**
     * The `production/factoryCalendar` resource.
     */
    public function factoryCalendar(): FactoryCalendarResource
    {
        return new FactoryCalendarResource($this->connector);
    }

    /**
     * The `production/productionBOM` resource, the production BOMs of products and product families.
     */
    public function productionBom(): ProductionBomResource
    {
        return new ProductionBomResource($this->connector);
    }

    /**
     * The `production/order` resource.
     */
    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }

    /**
     * The `production/orderList` resource.
     */
    public function orderList(): OrderListResource
    {
        return new OrderListResource($this->connector);
    }

    /**
     * The `production/resourceList` resource.
     */
    public function resourceList(): ResourceListResource
    {
        return new ResourceListResource($this->connector);
    }

    /**
     * The `production/resource` resource.
     */
    public function resource(): ResourceResource
    {
        return new ResourceResource($this->connector);
    }

    /**
     * The `production/suspendReason` resource.
     */
    public function suspendReason(): SuspendReasonResource
    {
        return new SuspendReasonResource($this->connector);
    }

    /**
     * The `production/workcenters` resource.
     */
    public function workCenters(): WorkCentersResource
    {
        return new WorkCentersResource($this->connector);
    }
}
