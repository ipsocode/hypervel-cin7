<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Discount Tag, one entry of a deal discount's `DealDiscountTags`: a product tag by
 * `TagName`. The table requires `Type`, but no example sends it, so it is optional.
 *
 * @see docs/data.md
 */
final class ProductDealDiscountTagData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $TagName = null,
        #[Required]
        public ?string $Type = null,
    ) {
    }
}
