<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\WorkCenters;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\WorkCenterLocationType;

/**
 * WorkCenterLocation, a location a work center consumes from or outputs to: a `LocationID`, or a
 * bin's ID with its `ParentLocationID`. `WorkCenterID` and `ParentLocationName` are in the
 * examples, not the table.
 *
 * @see docs/data.md
 */
final class WorkCenterLocationData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ID,
        #[Uuid]
        public string $LocationID,
        public WorkCenterLocationType $Type,
        public string $LocationName,
        #[Uuid]
        public ?string $WorkCenterID = null,
        #[Uuid]
        public ?string $ParentLocationID = null,
        public ?string $ParentLocationName = null,
    ) {
    }
}
