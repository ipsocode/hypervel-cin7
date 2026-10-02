<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * The body of `reference/discount` POST: the `DiscountRules` to create, each a
 * `ProductDiscountRulePostData`. The PUT body is one bare rule, `ProductDiscountRulePutData`.
 *
 * @see docs/data.md
 */
final class ProductDiscountRulesPostData extends Data
{
    /**
     * @param list<ProductDiscountRulePostData> $DiscountRules
     */
    public function __construct(
        #[DataCollectionOf(ProductDiscountRulePostData::class)]
        public array $DiscountRules,
    ) {
    }
}
