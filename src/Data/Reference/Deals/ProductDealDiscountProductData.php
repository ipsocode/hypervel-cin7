<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Discount Product, one entry of a deal discount's `DealDiscountProducts`: a product by
 * `ProductID` or `ProductSKU`. The table requires `IsFamily` and `Type`, but the examples send a
 * product with neither, so both are optional.
 *
 * @see docs/data.md
 */
final class ProductDealDiscountProductData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $ProductSKU = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductName = null,
        public ?bool $IsFamily = null,
        public ?string $Type = null,
    ) {
    }
}
