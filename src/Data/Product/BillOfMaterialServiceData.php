<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;

/**
 * Bill Of Material Service Model, one entry of a product's `BillOfMaterialsServices`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialServiceData extends Data
{
    public function __construct(
        public ?string $ComponentProductID = null,
        public ?string $Name = null,
        public ?float $Quantity = null,
        public ?string $ExpenseAccount = null,
        public ?int $PriceTier = null,
    ) {
    }
}
