<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Ref\Supplier\DepositsResource;

/**
 * Groups the `ref/supplier/…` resources; V2 has no action on `/ref/supplier` itself.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class SupplierResource extends BaseResource
{
    /**
     * The `ref/supplier/deposits` resource.
     */
    public function deposits(): DepositsResource
    {
        return new DepositsResource($this->connector);
    }
}
