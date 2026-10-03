<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Customer specific Product Price Model, a customer's `ProductPrices` and a product's `CustomPrices`.
 *
 * A price needs its `Price`, and, as the table's footnote says, either `ProductID` or
 * `ProductSKU` and either `CustomerID` or `CustomerName`, wherever it is nested: a write body
 * with a price missing either pair fails validation before it is sent.
 *
 * @see docs/data.md
 */
final class ProductPriceData extends Data
{
    public function __construct(
        public float $Price,
        #[RequiredWithout('ProductSKU')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('CustomerName')]
        #[Uuid]
        public ?string $CustomerID = null,
        #[RequiredWithout('CustomerID')]
        #[Max(256)]
        public ?string $CustomerName = null,
        #[RequiredWithout('ProductID')]
        #[Max(50)]
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
    ) {
    }
}
