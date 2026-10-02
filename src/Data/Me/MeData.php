<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\DimensionUnit;
use Ipsocode\Cin7\Enums\DiscountRule;
use Ipsocode\Cin7\Enums\TaxCalculationMethod;
use Ipsocode\Cin7\Enums\WeightUnit;

/**
 * ME, the `me` response: the company's name, base currency and time zone, and the settings its
 * documents follow, with its `RoundingTable`. The reference requires none of its fields.
 *
 * @see docs/data.md
 */
final class MeData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<RoundingTableData> $RoundingTable
     */
    public function __construct(
        public ?string $Company = null,
        public ?string $Currency = null,
        public ?string $TimeZone = null,
        public ?WeightUnit $DefaultWeightUnits = null,
        public ?DimensionUnit $DefaultDimensionsUnits = null,
        #[Date]
        public ?string $LockDate = null,
        #[Date]
        public ?string $OpeningBalanceDate = null,
        public ?TaxCalculationMethod $TaxCalculationMethod = null,
        #[Uuid]
        public ?string $DefaultSaleTaxRuleId = null,
        public ?string $DefaultSaleTaxRuleName = null,
        public ?string $MaximumDecimalPlacesInQuantity = null,
        public ?bool $ApplyCustomerDiscountsAfterOtherDiscounts = null,
        public ?DiscountRule $DiscountRule = null,
        public ?bool $AutomaticallyApplyDiscounts = null,
        #[DataCollectionOf(RoundingTableData::class)]
        public ?array $RoundingTable = null,
    ) {
    }
}
