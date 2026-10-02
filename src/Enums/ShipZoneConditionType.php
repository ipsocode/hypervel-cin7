<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * What a ship zone condition's range is measured in.
 *
 * @see docs/data.md
 */
enum ShipZoneConditionType: string
{
    case Price = 'Price';
    case Weight = 'Weight';
}
