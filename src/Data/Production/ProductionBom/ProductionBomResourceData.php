<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ResourceCostCalculationType;

/**
 * ProductionBOMResource, a resource of a BOM operation. `BOMResourceID` is required when updating;
 * `ResourceName`, `ResourceCode`, `Cost` and `CycleTime` are read-only.
 *
 * @see docs/data.md
 */
final class ProductionBomResourceData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ResourceID,
        public float $Quantity,
        public int $Position,
        public ResourceCostCalculationType $CostCalculationType,
        #[Uuid]
        public ?string $BOMResourceID = null,
        public ?string $ResourceName = null,
        public ?string $ResourceCode = null,
        public ?float $Cost = null,
        public ?int $CycleTime = null,
    ) {
    }
}
