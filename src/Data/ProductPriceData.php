<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;

/**
 * Customer specific Product Price Model, a customer's `ProductPrices` and a product's `CustomPrices`.
 *
 * @see docs/data.md
 */
final class ProductPriceData extends Data
{
    public function __construct(
        public ?string $ProductID = null,
        public ?string $CustomerID = null,
        public ?string $CustomerName = null,
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?float $Price = null,
    ) {
    }
}
