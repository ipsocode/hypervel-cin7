<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Supplier Options Interval Model, one entry of a supplier option's `SupplyIntervals`.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionIntervalData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Max(256)]
        public ?string $DeliveryMethod = null,
        public ?int $IntervalDays = null,
        #[Date]
        public ?string $IntervalStartDate = null,
        public ?bool $IsMonday = null,
        public ?bool $IsTuesday = null,
        public ?bool $IsWednesday = null,
        public ?bool $IsThursday = null,
        public ?bool $IsFriday = null,
        public ?bool $IsSaturday = null,
        public ?bool $IsSunday = null,
    ) {
    }
}
