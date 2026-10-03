<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOperationComponent, a component of a run operation: a product by `ProductID`, which
 * has priority, or `ProductCode`, and a location by `LocationID` or `LocationName`
 * (`Location:Bin`). `RunComponentID`, the product's name, costs, costing method and unit, and the
 * reserved quantity are read-only; `ProductCost` is typed String in the table and a number in the
 * examples.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationComponentData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $RunComponentID = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?float $Quantity = null,
        public ?float $ExpectedQuantity = null,
        public ?float $WastageQty = null,
        public ?float $WastagePercent = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?float $UnitCost = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?float $ProductCost = null,
        public ?float $Available = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?float $ReservedQuantity = null,
        public ?bool $IsReserved = null,
        public ?bool $IsBackflush = null,
    ) {
    }
}
