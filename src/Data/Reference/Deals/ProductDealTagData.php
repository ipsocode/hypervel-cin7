<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Deal Customer Tag, one entry of a deal's `DealCustomerTags`: a customer tag by `TagName`,
 * and the `ID` of the link.
 *
 * @see docs/data.md
 */
final class ProductDealTagData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $TagName = null,
    ) {
    }
}
