<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

/**
 * The body of `reference/shipZones` POST: the Shipping Zone table without the `ZoneID` Cin7
 * assigns. The table requires `DefaultShippingCost`, but the POST example sends none, so it stays
 * optional. The PUT body is `ShippingZonePutData`.
 *
 * @see docs/data.md
 */
final class ShippingZonePostData extends AbstractShippingZoneData
{
    public function __construct(
        string $Name,
        public bool $IsRestZone,
        public bool $PricesInclTax,
        public bool $Negative,
        public ?float $DefaultShippingCost = null,
    ) {
        parent::__construct($Name);
    }
}
