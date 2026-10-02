<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\DiscountRuleType;

/**
 * The body of `reference/discount` PUT: one bare rule, the Product Discount Rule table with the `ID`
 * of the rule to change, which PUT requires. Its example sends only the `ID`, `Name` and
 * `DiscountLines`, so `IsActive` and `Type`, which the table requires, are optional here. The POST
 * body is `ProductDiscountRulesPostData`.
 *
 * @see docs/data.md
 */
final class ProductDiscountRulePutData extends AbstractProductDiscountRuleData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
        public ?bool $IsActive = null,
        public ?DiscountRuleType $Type = null,
    ) {
        parent::__construct($Name);
    }
}
