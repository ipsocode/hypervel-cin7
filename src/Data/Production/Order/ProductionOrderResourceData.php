<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ResourceCostCalculationType;

/**
 * ProductionOrderResource, a resource of a production order operation: a resource by `ResourceID`
 * or `ResourceCode`. `OrderResourceID` is required when updating; the resource's name and costs
 * are read-only.
 *
 * @see docs/data.md
 */
final class ProductionOrderResourceData extends Data
{
    public function __construct(
        public int $Position,
        public float $Quantity,
        public ResourceCostCalculationType $CostCalculationType,
        #[Uuid]
        public ?string $OrderResourceID = null,
        #[RequiredWithout('ResourceCode')]
        #[Uuid]
        public ?string $ResourceID = null,
        #[RequiredWithout('ResourceID')]
        public ?string $ResourceCode = null,
        public ?string $ResourceName = null,
        public ?float $Cost = null,
        public ?float $TotalCost = null,
        public ?float $ResourceCost = null,
        public ?int $CycleTime = null,
    ) {
    }
}
