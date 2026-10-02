<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZones;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields of the Shipping Zone table: the response of every `reference/shipZones` action and the
 * body of its POST and PUT. Each is a final child that adds the fields it requires.
 *
 * Every zone needs its `Name`, so each child passes it to this constructor; the optional fields
 * declared here are set through `from()`. `TaxRuleID` and `TaxRuleName` are "Yes*" and name the same
 * rule, so both stay optional. `ShortDesc` is a Bool in the table, but its example is a string: a
 * description of the zone's areas, read-only.
 *
 * @see docs/data.md
 */
abstract class AbstractShippingZoneData extends Data
{
    public ?string $ShortDesc = null;

    #[Uuid]
    public ?string $TaxRuleID = null;

    #[Max(255)]
    public ?string $TaxRuleName = null;

    /**
     * @var null|list<ShipZoneAppliesToData>
     */
    #[DataCollectionOf(ShipZoneAppliesToData::class)]
    public ?array $AppliesTo = null;

    /**
     * @var null|list<ShipZoneConditionData>
     */
    #[DataCollectionOf(ShipZoneConditionData::class)]
    public ?array $Conditions = null;

    public function __construct(
        #[Max(128)]
        public string $Name,
    ) {
    }
}
