<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Customer specific Product Price Model, a customer's `ProductPrices` and a product's `CustomPrices`.
 *
 * @see docs/data.md
 */
final class ProductPriceData extends Data
{
    public function __construct(
        public string|Optional $ProductID,
        public string|Optional $CustomerID,
        public string|Optional $CustomerName,
        public string|Optional $ProductSKU,
        public string|Optional $ProductName,
        public float|Optional $Price,
    ) {
    }
}
