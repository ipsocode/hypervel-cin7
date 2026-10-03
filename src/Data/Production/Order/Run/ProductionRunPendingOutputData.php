<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunPendingOutput, a planned finished product of a run: a product by `ProductID`, which
 * has priority, or `ProductCode`. `ExpiryDate` is required for a product costed `FEBATCH` or
 * `FESN`.
 *
 * @see docs/data.md
 */
final class ProductionRunPendingOutputData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?string $Unit = null,
        public ?string $CostingMethod = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
    ) {
    }
}
