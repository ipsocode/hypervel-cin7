<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Ipsocode\Cin7\Enums\DiscountRuleType;

/**
 * One rule of the body of `reference/discount` POST: the Product Discount Rule table without the `ID`
 * Cin7 assigns. The body itself is `ProductDiscountRulesPostData`, a list of them.
 *
 * @see docs/data.md
 */
final class ProductDiscountRulePostData extends AbstractProductDiscountRuleData
{
    public function __construct(
        string $Name,
        public bool $IsActive,
        public DiscountRuleType $Type,
    ) {
        parent::__construct($Name);
    }
}
