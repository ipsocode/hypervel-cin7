<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A unit of length, the `DimensionsUnits` of the reference's Dimension Unit Available Values. The
 * values are the abbreviations Cin7 sends; the cases are named after the units the table names.
 *
 * @see docs/data.md
 */
enum DimensionUnit: string
{
    case Metre = 'm';
    case Centimetre = 'cm';
    case Mile = 'mi';
    case Millimetre = 'mm';
    case Inch = 'in';
    case Foot = 'ft';
    case Yard = 'yd';
    case Kilometre = 'km';
}
