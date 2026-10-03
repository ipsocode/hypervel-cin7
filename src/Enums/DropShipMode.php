<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether a product is drop-shipped.
 *
 * @see docs/data.md
 */
enum DropShipMode: string
{
    case NoDropShip = 'No Drop Ship';
    case OptionalDropShip = 'Optional Drop Ship';
    case AlwaysDropShip = 'Always Drop Ship';
}
