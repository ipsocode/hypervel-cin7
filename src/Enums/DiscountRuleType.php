<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a product discount rule.
 *
 * @see docs/data.md
 */
enum DiscountRuleType: string
{
    case Simple = 'Simple';
    case QuantityBased = 'QuantityBased';
    case FreeShipping = 'FreeShipping';
}
