<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMComponent, a component of a BOM operation: a product by `ProductID` or
 * `ProductSKU`. `BOMComponentID` is required when updating; the product's name, cost, costing
 * method and unit are read-only. `WastageQty` and `WastagePercent` stand for each other.
 *
 * @see docs/data.md
 */
final class ProductionBomComponentData extends Data
{
    public function __construct(
        public float $Quantity,
        public int $Position,
        #[Uuid]
        public ?string $BOMComponentID = null,
        #[RequiredWithout('ProductSKU')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?float $WastageQty = null,
        public ?float $WastagePercent = null,
        public ?float $Cost = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?int $SalePriceTier = null,
        public ?bool $IsBackflush = null,
    ) {
    }
}
