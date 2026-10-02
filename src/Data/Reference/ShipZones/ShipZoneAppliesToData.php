<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Ship Zone Applies To Model, one entry of a ship zone's `AppliesTo`: a country, state or postcode
 * range the zone covers, and the `ShippingRate` there. The table requires the `ID`, but the POST
 * example sends none for an area it adds, so it stays optional.
 *
 * @see docs/data.md
 */
final class ShipZoneAppliesToData extends Data
{
    public function __construct(
        public float $ShippingRate,
        #[Uuid]
        public ?string $ID = null,
        public ?string $Country2 = null,
        public ?int $StateID = null,
        public ?string $StateName = null,
        #[Max(100)]
        public ?string $PostCodeFrom = null,
        #[Max(100)]
        public ?string $PostCodeTo = null,
        public ?string $PostCodesList = null,
    ) {
    }
}
