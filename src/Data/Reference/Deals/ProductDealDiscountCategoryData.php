<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Discount Category, one entry of a deal discount's `DealDiscountCategories`: a
 * category by `CategoryID` or `CategoryName`. The table has no `CategoryID`, but the examples send
 * it; and it requires `Type`, which no example sends, so it is optional.
 *
 * @see docs/data.md
 */
final class ProductDealDiscountCategoryData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $CategoryName = null,
        #[Uuid]
        public ?string $CategoryID = null,
        #[Required]
        public ?string $Type = null,
    ) {
    }
}
