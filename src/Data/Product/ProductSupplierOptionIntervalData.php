<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DeliveryMethod;

/**
 * Product Supplier Options Interval Model, one entry of a supplier option's `SupplyIntervals`. As
 * the table's notes say, an `Interval` delivery needs its `IntervalDays` and `IntervalStartDate`,
 * and a `Fixed` one each `Is…` day, so a write body without them fails validation.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionIntervalData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?DeliveryMethod $DeliveryMethod = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Interval)]
        public ?int $IntervalDays = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Interval)]
        #[Date]
        public ?string $IntervalStartDate = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsMonday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsTuesday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsWednesday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsThursday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsFriday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsSaturday = null,
        #[RequiredIf('DeliveryMethod', DeliveryMethod::Fixed)]
        public ?bool $IsSunday = null,
    ) {
    }
}
