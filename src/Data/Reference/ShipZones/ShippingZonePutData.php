<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `reference/shipZones` PUT: the Shipping Zone table with the `ZoneID` of the zone to
 * change, which PUT requires. Its example sends only the `ZoneID`, `Name` and `Conditions`, so
 * `IsRestZone`, `PricesInclTax`, `Negative` and `DefaultShippingCost`, which the table requires,
 * are optional here: a partial update leaves them as they are. The POST body is
 * `ShippingZonePostData`.
 *
 * @see docs/data.md
 */
final class ShippingZonePutData extends AbstractShippingZoneData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ZoneID,
        public ?bool $IsRestZone = null,
        public ?bool $PricesInclTax = null,
        public ?bool $Negative = null,
        public ?float $DefaultShippingCost = null,
    ) {
        parent::__construct($Name);
    }
}
