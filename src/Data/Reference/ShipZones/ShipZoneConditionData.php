<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ShipZoneConditionType;

/**
 * Condition Model (the reference's link says `ShipZoneConditionsModel`), one entry of a ship zone's
 * `Conditions`: a range of `ConditionType` (`Price` or `Weight`) and the `ShippingCost` in it. The
 * table requires the `ZoneConditionID`, but the examples send none for a condition they add, so it
 * stays optional.
 *
 * @see docs/data.md
 */
final class ShipZoneConditionData extends Data
{
    public function __construct(
        public float $ShippingCost,
        public ShipZoneConditionType $ConditionType,
        #[Uuid]
        public ?string $ZoneConditionID = null,
        public ?float $MinValue = null,
        public ?float $MaxValue = null,
        #[Max(128)]
        public ?string $ConditionDescription = null,
    ) {
    }
}
