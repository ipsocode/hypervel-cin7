<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderComponent, a component of a production order operation: a product by `ProductID`
 * or `ProductSKU`. `OrderComponentID` is required when updating; the product's name, availability,
 * costs, unit and type are read-only.
 *
 * @see docs/data.md
 */
final class ProductionOrderComponentData extends Data
{
    public function __construct(
        public int $Position,
        public float $Quantity,
        #[Uuid]
        public ?string $OrderComponentID = null,
        #[RequiredWithout('ProductSKU')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?float $Available = null,
        public ?float $TotalQuantity = null,
        public ?float $WastageQty = null,
        public ?float $WastagePercent = null,
        public ?float $Cost = null,
        public ?float $TotalCost = null,
        public ?float $ProductCost = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?string $ProductType = null,
        public ?int $SalePriceTier = null,
        public ?bool $IsBackflush = null,
        public ?bool $IsAlternative = null,
    ) {
    }
}
