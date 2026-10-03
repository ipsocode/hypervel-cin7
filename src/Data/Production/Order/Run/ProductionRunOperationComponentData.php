<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasProductionProductFields;

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
    use HasProductionProductFields;

    public function __construct(
        #[Uuid]
        public ?string $RunComponentID = null,
        public ?float $Quantity = null,
        public ?float $ExpectedQuantity = null,
        public ?float $WastageQty = null,
        public ?float $WastagePercent = null,
        public ?float $UnitCost = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?float $ProductCost = null,
        public ?float $Available = null,
        public ?string $CostingMethod = null,
        public ?float $ReservedQuantity = null,
        public ?bool $IsReserved = null,
        public ?bool $IsBackflush = null,
    ) {
    }
}
