<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Bill Of Material Service Model, one entry of a product's `BillOfMaterialsServices`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialServiceData extends Data
{
    public function __construct(
        public string|Optional $ComponentProductID,
        public string|Optional $Name,
        public float|Optional $Quantity,
        public string|Optional|null $ExpenseAccount,
        public int|Optional $PriceTier,
    ) {
    }
}
