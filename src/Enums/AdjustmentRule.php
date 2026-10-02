<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a rounding table row adjusts a rounded price. The values are the letters Cin7 sends; the
 * cases are named after the rules the notes give them.
 *
 * @see docs/data.md
 */
enum AdjustmentRule: string
{
    case NoAdjustment = 'N';
    case Subtract = 'S';
    case Add = 'A';
}
