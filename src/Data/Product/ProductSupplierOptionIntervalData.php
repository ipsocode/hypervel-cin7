<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;

/**
 * Product Supplier Options Interval Model, one entry of a supplier option's `SupplyIntervals`.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionIntervalData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $DeliveryMethod = null,
        public ?int $IntervalDays = null,
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
