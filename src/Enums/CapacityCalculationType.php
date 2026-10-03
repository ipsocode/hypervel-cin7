<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a production order's capacity is calculated.
 *
 * @see docs/data.md
 */
enum CapacityCalculationType: string
{
    case FromStartForward = 'FromStartForward';
    case FromRequiredBackward = 'FromRequiredBackward';
}
