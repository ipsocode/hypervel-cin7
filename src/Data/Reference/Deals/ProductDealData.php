<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Product Deal, one entry of `Deals` in every `reference/deals` response: the table, which requires
 * its `ID`, `Name`, `IsActive`, `AllowCoupons` and `SingleCouponCodeUsage`. The bodies of POST and
 * PUT are `ProductDealPostData` and `ProductDealPutData`.
 *
 * @see docs/data.md
 */
final class ProductDealData extends AbstractProductDealData implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $ID,
        string $Name,
        public bool $IsActive,
        public bool $AllowCoupons,
        public bool $SingleCouponCodeUsage,
    ) {
        parent::__construct($Name);
    }
}
