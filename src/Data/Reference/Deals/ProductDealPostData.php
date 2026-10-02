<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

/**
 * The body of `reference/deals` POST: the Product Deal table without the `ID` Cin7 assigns. The
 * table requires `IsActive`, `AllowCoupons` and `SingleCouponCodeUsage`, but the POST example sends
 * none of them, so they are optional. The PUT body is `ProductDealPutData`.
 *
 * @see docs/data.md
 */
final class ProductDealPostData extends AbstractProductDealData
{
    public function __construct(
        string $Name,
        public ?bool $IsActive = null,
        public ?bool $AllowCoupons = null,
        public ?bool $SingleCouponCodeUsage = null,
    ) {
        parent::__construct($Name);
    }
}
