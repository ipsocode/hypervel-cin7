<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Bill Of Material Product Model, one entry of a product's `BillOfMaterialsProducts`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialProductData extends Data
{
    public function __construct(
        public string|Optional $ComponentProductID,
        public string|Optional $ProductCode,
        public string|Optional $Name,
        public float|Optional $Quantity,
        public float|Optional $WastagePercent,
        public float|Optional $WastageQuantity,
        public float|Optional $CostPercentage,
    ) {
    }
}
