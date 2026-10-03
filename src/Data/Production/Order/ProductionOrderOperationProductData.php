<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderOperationProduct, a read-only input, output or finished product of a production
 * order operation; only its deletion is allowed.
 *
 * @see docs/data.md
 */
final class ProductionOrderOperationProductData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?int $Position = null,
        public ?string $CostCalculationType = null,
        public ?int $PriceTier = null,
        public ?float $Ratio = null,
        public ?float $OutputQuantity = null,
        public ?float $TotalOutputQuantity = null,
        public ?float $Cost = null,
        public ?float $TotalCost = null,
        public ?float $AverageCost = null,
        public ?float $FixedCost = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?float $WastageCost = null,
    ) {
    }
}
