<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Ref\AccountResource;
use Ipsocode\Cin7\Resources\Ref\CustomerResource;
use Ipsocode\Cin7\Resources\Ref\SupplierResource;
use Ipsocode\Cin7\Resources\Ref\TaxResource;

/**
 * Groups the `ref/…` resources; V2 has no action on `/ref` itself.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class RefResource extends BaseResource
{
    /**
     * The `ref/tax` resource.
     */
    public function tax(): TaxResource
    {
        return new TaxResource($this->connector);
    }

    /**
     * The `ref/customer` grouping.
     */
    public function customer(): CustomerResource
    {
        return new CustomerResource($this->connector);
    }

    /**
     * The `ref/supplier` grouping.
     */
    public function supplier(): SupplierResource
    {
        return new SupplierResource($this->connector);
    }

    /**
     * The `ref/account` resource, the chart of accounts.
     */
    public function account(): AccountResource
    {
        return new AccountResource($this->connector);
    }
}
