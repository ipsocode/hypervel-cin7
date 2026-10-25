<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a stock transfer's additional journals are capitalized across its lines.
 *
 * @see src/docs/data.md
 */
enum CostDistributionType: string
{
    case Cost = 'Cost';
    case Quantity = 'Quantity';
    case Weight = 'Weight';
    case Volume = 'Volume';
}
