<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Bill Of Material Product Model, one entry of a product's `BillOfMaterialsProducts`. It needs
 * its `Quantity`, and a component named by `ComponentProductID` or `ProductCode`.
 *
 * @see docs/data.md
 */
final class BillOfMaterialProductData extends Data
{
    public function __construct(
        public float $Quantity,
        #[RequiredWithout('ProductCode')]
        #[Uuid]
        public ?string $ComponentProductID = null,
        #[RequiredWithout('ComponentProductID')]
        #[Max(256)]
        public ?string $ProductCode = null,
        #[Max(256)]
        public ?string $Name = null,
        public ?float $WastagePercent = null,
        public ?float $WastageQuantity = null,
        public ?float $CostPercentage = null,
    ) {
    }
}
