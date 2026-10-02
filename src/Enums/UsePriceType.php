<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The start price a markup is applied to: the product's average cost, its latest fixed or latest
 * supplier cost, or, for `V`, the `MarkupValue` itself as the calculated price.
 *
 * @see docs/data.md
 */
enum UsePriceType: string
{
    case AverageCost = 'A';
    case FixedSupplierCost = 'F';
    case LatestSupplierCost = 'L';
    case Value = 'V';
}
