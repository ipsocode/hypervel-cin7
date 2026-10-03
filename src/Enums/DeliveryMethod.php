<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a product supplier option's supply interval repeats.
 *
 * @see docs/data.md
 */
enum DeliveryMethod: string
{
    case Fixed = 'Fixed';
    case Interval = 'Interval';
}
