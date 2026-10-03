<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionRunOperationResource, a resource of a run operation: a resource by `ResourceID`, which
 * has priority, or `ResourceCode`. `RunResourceID` is required when updating; the name,
 * `UnitCost`, `Cost` and `CycleTime` are read-only. `ResourceCode` is typed Guid in the table,
 * copied from the field above it: it is a code, a string. `Available` is in the examples, not the
 * table.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationResourceData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $RunResourceID = null,
        #[Uuid]
        public ?string $ResourceID = null,
        public ?string $ResourceCode = null,
        #[Max(50)]
        public ?string $ResourceName = null,
        public ?float $UnitCost = null,
        public ?float $Quantity = null,
        public ?float $Cost = null,
        public ?float $CycleTime = null,
        public ?float $Available = null,
    ) {
    }
}
