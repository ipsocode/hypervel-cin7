<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How the company applies a discount to an eligible sale line: by recalculating the line's
 * discount % or by amending its price. The product's `DiscountRule`, the name of a discount, is a
 * string.
 *
 * @see docs/data.md
 */
enum DiscountRule: string
{
    case Discount = 'Discount';
    case Price = 'Price';
}
