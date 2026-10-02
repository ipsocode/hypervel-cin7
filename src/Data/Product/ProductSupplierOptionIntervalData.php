<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Product Supplier Options Interval Model, one entry of a supplier option's `SupplyIntervals`.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionIntervalData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $DeliveryMethod,
        public int|Optional $IntervalDays,
        public string|Optional|null $IntervalStartDate,
        public bool|Optional $IsMonday,
        public bool|Optional $IsTuesday,
        public bool|Optional $IsWednesday,
        public bool|Optional $IsThursday,
        public bool|Optional $IsFriday,
        public bool|Optional $IsSaturday,
        public bool|Optional $IsSunday,
    ) {
    }
}
