<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A unit of weight, the `WeightUnits` of the reference's Dimension Unit Available Values. The
 * values are the abbreviations Cin7 sends; the cases are named after the units the table names.
 *
 * @see docs/data.md
 */
enum WeightUnit: string
{
    case Ounce = 'oz';
    case Milligram = 'mg';
    case Kilogram = 'kg';
    case Pound = 'lb';
    case Gram = 'g';
}
