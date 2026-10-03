<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ResourceCost, the cost of a resource: a service `ProductID`, an expense `AccountCode` and a
 * `PriceTier` (1 to 10). `ResourceCostID`, `ProductName` and the costs are read-only.
 *
 * @see docs/data.md
 */
final class ResourceCostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ProductID,
        #[Max(50)]
        public string $AccountCode,
        public int $PriceTier,
        #[Uuid]
        public ?string $ResourceCostID = null,
        public ?string $ProductName = null,
        public ?float $Cost = null,
        public ?float $PlannedDowntimeCost = null,
        public ?int $PlannedDowntimePriceTier = null,
        public ?float $NotPlannedDowntimeCost = null,
        public ?int $NotPlannedDowntimePriceTier = null,
    ) {
    }
}
