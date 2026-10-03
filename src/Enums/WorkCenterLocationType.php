<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * What a work center location is used for.
 *
 * @see docs/data.md
 */
enum WorkCenterLocationType: string
{
    case Consumption = 'Consumption';
    case Output = 'Output';
}
