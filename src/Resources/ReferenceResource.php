<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Reference\DealsResource;
use Ipsocode\Cin7\Resources\Reference\DiscountResource;
use Ipsocode\Cin7\Resources\Reference\ShipZonesEnabledResource;
use Ipsocode\Cin7\Resources\Reference\ShipZonesResource;

/**
 * The `reference/…` resources, the reference books: `deals()`, `discount()`, `shipZones()` and `shipZonesEnabled()`. They are
 * apart from `ref()`, whose paths are `ref/…`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ReferenceResource extends BaseResource
{
    /**
     * The `reference/deals` resource.
     */
    public function deals(): DealsResource
    {
        return new DealsResource($this->connector);
    }

    /**
     * The `reference/discount` resource.
     */
    public function discount(): DiscountResource
    {
        return new DiscountResource($this->connector);
    }

    /**
     * The `reference/shipZones` resource.
     */
    public function shipZones(): ShipZonesResource
    {
        return new ShipZonesResource($this->connector);
    }

    /**
     * The `reference/shipZonesEnabled` resource.
     */
    public function shipZonesEnabled(): ShipZonesEnabledResource
    {
        return new ShipZonesEnabledResource($this->connector);
    }
}
