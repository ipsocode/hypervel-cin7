<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a production resource's cost is calculated.
 *
 * @see docs/data.md
 */
enum ResourceCostCalculationType: string
{
    case CostPerUnitTime = 'CostPerUnitTime';
    case CostPerFinishedProduct = 'CostPerFinishedProduct';
}
