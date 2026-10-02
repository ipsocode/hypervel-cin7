<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product\MarkupPrices;

use Hypervel\Data\Attributes\Validation\Between;
use Hypervel\Data\Attributes\Validation\Min;
use Hypervel\Data\Attributes\Validation\RequiredUnless;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\MarkupType;
use Ipsocode\Cin7\Enums\UsePriceType;

/**
 * Markup Price Line Model: the markup of one price tier. A tier with `MarkupType::Deleted` has no
 * start price or value; any other needs both.
 *
 * @see docs/data.md
 */
final class MarkupPriceLineData extends Data
{
    public function __construct(
        #[Between(1, 10)]
        public int $TierNumber,
        public MarkupType $MarkupType,
        #[RequiredUnless('MarkupType', MarkupType::Deleted)]
        public ?UsePriceType $UsePriceType = null,
        #[RequiredUnless('MarkupType', MarkupType::Deleted)]
        #[Min(0)]
        public ?float $MarkupValue = null,
    ) {
    }
}
