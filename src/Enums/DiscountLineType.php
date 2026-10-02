<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * What a discount line does to the price.
 *
 * @see docs/data.md
 */
enum DiscountLineType: string
{
    case DiscountAmount = 'DiscountAmount';
    case DiscountPercent = 'DiscountPercent';
    case MarkupAmount = 'MarkupAmount';
    case MarkupPercent = 'MarkupPercent';
    case PriceOverride = 'PriceOverride';
    case FlatAmount = 'FlatAmount';
    case FreeShipping = 'FreeShipping';
}
