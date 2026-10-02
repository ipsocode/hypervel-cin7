<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Bill Of Material Service Model, one entry of a product's `BillOfMaterialsServices`. It needs
 * its `Quantity`, and a service named by `ComponentProductID` or `Name`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialServiceData extends Data
{
    public function __construct(
        public float $Quantity,
        #[RequiredWithout('Name')]
        #[Uuid]
        public ?string $ComponentProductID = null,
        #[RequiredWithout('ComponentProductID')]
        #[Max(256)]
        public ?string $Name = null,
        public ?string $ExpenseAccount = null,
        public ?int $PriceTier = null,
    ) {
    }
}
