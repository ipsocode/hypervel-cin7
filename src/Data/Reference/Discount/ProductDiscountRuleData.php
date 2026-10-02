<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\DiscountRuleType;

/**
 * Product Discount Rule, one entry of `DiscountRules` in every `reference/discount` response: the
 * table, which requires its `ID`, `Name`, `IsActive` and `Type`. The bodies of POST and PUT are
 * `ProductDiscountRulesPostData` and `ProductDiscountRulePutData`.
 *
 * @see docs/data.md
 */
final class ProductDiscountRuleData extends AbstractProductDiscountRuleData implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $ID,
        string $Name,
        public bool $IsActive,
        public DiscountRuleType $Type,
    ) {
        parent::__construct($Name);
    }
}
