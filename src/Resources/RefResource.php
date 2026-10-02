<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Ref\AccountResource;
use Ipsocode\Cin7\Resources\Ref\BrandResource;
use Ipsocode\Cin7\Resources\Ref\CategoryResource;
use Ipsocode\Cin7\Resources\Ref\CustomerResource;
use Ipsocode\Cin7\Resources\Ref\FixedAssetTypeResource;
use Ipsocode\Cin7\Resources\Ref\PaymentTermResource;
use Ipsocode\Cin7\Resources\Ref\PriceTierResource;
use Ipsocode\Cin7\Resources\Ref\ProductAvailabilityResource;
use Ipsocode\Cin7\Resources\Ref\SupplierResource;
use Ipsocode\Cin7\Resources\Ref\TaxResource;
use Ipsocode\Cin7\Resources\Ref\UnitResource;

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

    /**
     * The `ref/fixedassettype` resource.
     */
    public function fixedAssetType(): FixedAssetTypeResource
    {
        return new FixedAssetTypeResource($this->connector);
    }

    /**
     * The `ref/paymentterm` resource.
     */
    public function paymentTerm(): PaymentTermResource
    {
        return new PaymentTermResource($this->connector);
    }

    /**
     * The `ref/brand` resource.
     */
    public function brand(): BrandResource
    {
        return new BrandResource($this->connector);
    }

    /**
     * The `ref/category` resource.
     */
    public function category(): CategoryResource
    {
        return new CategoryResource($this->connector);
    }

    /**
     * The `ref/unit` resource.
     */
    public function unit(): UnitResource
    {
        return new UnitResource($this->connector);
    }

    /**
     * The `ref/priceTier` resource.
     */
    public function priceTier(): PriceTierResource
    {
        return new PriceTierResource($this->connector);
    }

    /**
     * The `ref/productavailability` resource.
     */
    public function productAvailability(): ProductAvailabilityResource
    {
        return new ProductAvailabilityResource($this->connector);
    }
}
