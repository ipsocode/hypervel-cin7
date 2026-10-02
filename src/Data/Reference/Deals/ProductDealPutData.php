<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Deals;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `reference/deals` PUT: the Product Deal table with the `ID` of the deal to change,
 * which PUT requires. Its example sends no `IsActive`, `AllowCoupons` or `SingleCouponCodeUsage`, so
 * they are optional. The POST body is `ProductDealPostData`.
 *
 * @see docs/data.md
 */
final class ProductDealPutData extends AbstractProductDealData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
        public ?bool $IsActive = null,
        public ?bool $AllowCoupons = null,
        public ?bool $SingleCouponCodeUsage = null,
    ) {
        parent::__construct($Name);
    }
}
