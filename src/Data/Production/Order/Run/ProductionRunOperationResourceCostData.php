<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionRunOperationResourceCost, the cost of a resource's service product in a run operation:
 * required `RunCostID`, `ProductID` and `Cost`.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationResourceCostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $RunCostID,
        #[Uuid]
        public string $ProductID,
        public float $Cost,
        public ?string $ProductName = null,
        #[Max(50)]
        public ?string $AccountName = null,
        #[Max(50)]
        public ?string $ExpenseAccount = null,
        #[Max(256)]
        public ?string $Comments = null,
    ) {
    }
}
