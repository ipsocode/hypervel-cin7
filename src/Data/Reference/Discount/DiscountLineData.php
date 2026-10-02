<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DiscountLineType;

/**
 * Discount Line Model, one entry of a discount rule's `DiscountLines`. `MinValue` and `MaxValue` are
 * for a quantity based rule only, and Cin7 works out each `MinValue` after the first as the previous
 * `MaxValue` + 0.0001. `OrderExceeds` is required when `DiscountType` is `FreeShipping`. The table
 * requires the `ID`, but the POST example sends none for a line it adds, so it is optional.
 *
 * @see docs/data.md
 */
final class DiscountLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?float $MinValue = null,
        public ?float $MaxValue = null,
        public ?DiscountLineType $DiscountType = null,
        public ?float $Amount = null,
        #[RequiredIf('DiscountType', DiscountLineType::FreeShipping)]
        public ?float $OrderExceeds = null,
    ) {
    }
}
