<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a fixed asset type depreciates its assets.
 *
 * @see docs/data.md
 */
enum DepreciationMethod: string
{
    case NoDepreciation = 'No Depreciation';
    case StraightLine = 'Straight Line';
    case DecliningBalance = 'Declining Balance';
    case DecliningBalance150 = 'Declining Balance (150%)';
    case DecliningBalance200 = 'Declining Balance (200%)';
    case SumOfTheYearsDigits = 'Sum of the Years\' Digits';
    case FullDepreciationAtPurchase = 'Full Depreciation at Purchase';
}
