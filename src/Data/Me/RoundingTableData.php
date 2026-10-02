<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AdjustmentRule;

/**
 * Rounding Table Model, one row of the company's `RoundingTable`: a price up to `RangeTo` is
 * rounded to `RoundToNearest`, then adjusted by `AdjustmentRule` and `AdjustmentValue`.
 *
 * The reference types `AdjustmentValue` as String, but its example sends a number, so it
 * accepts both.
 *
 * @see docs/data.md
 */
final class RoundingTableData extends Data
{
    public function __construct(
        public ?float $RangeTo = null,
        public ?float $RoundToNearest = null,
        public ?AdjustmentRule $AdjustmentRule = null,
        public string|float|null $AdjustmentValue = null,
    ) {
    }
}
