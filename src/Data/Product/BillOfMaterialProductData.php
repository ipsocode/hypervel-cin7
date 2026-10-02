<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;

/**
 * Bill Of Material Product Model, one entry of a product's `BillOfMaterialsProducts`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialProductData extends Data
{
    public function __construct(
        public ?string $ComponentProductID = null,
        public ?string $ProductCode = null,
        public ?string $Name = null,
        public ?float $Quantity = null,
        public ?float $WastagePercent = null,
        public ?float $WastageQuantity = null,
        public ?float $CostPercentage = null,
    ) {
    }
}
