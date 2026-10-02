<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Shipping Zone, one entry of `ShipZones` in every `reference/shipZones` response: the table, which
 * requires every field but the `ShortDesc`, the tax rule and the lists. The bodies of POST and PUT
 * are `ShippingZonePostData` and `ShippingZonePutData`.
 *
 * @see docs/data.md
 */
final class ShippingZoneData extends AbstractShippingZoneData implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $ZoneID,
        string $Name,
        public bool $IsRestZone,
        public bool $PricesInclTax,
        public bool $Negative,
        public float $DefaultShippingCost,
    ) {
        parent::__construct($Name);
    }
}
