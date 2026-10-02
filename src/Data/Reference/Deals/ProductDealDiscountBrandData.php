<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Discount Brand, one entry of a deal discount's `DealDiscountBrands`: a brand by
 * `BrandID` or `BrandName`. The table requires `Type`, but no example sends it, so it is optional.
 *
 * @see docs/data.md
 */
final class ProductDealDiscountBrandData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $BrandName = null,
        #[Uuid]
        public ?string $BrandID = null,
        public ?string $Type = null,
    ) {
    }
}
