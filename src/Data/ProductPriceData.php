<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Customer specific Product Price Model, a customer's `ProductPrices` and a product's `CustomPrices`.
 *
 * @see docs/data.md
 */
final class ProductPriceData extends Data
{
    public function __construct(
        public float $Price,
        #[Uuid]
        public ?string $ProductID = null,
        #[Uuid]
        public ?string $CustomerID = null,
        #[Max(256)]
        public ?string $CustomerName = null,
        #[Max(50)]
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
    ) {
    }
}
