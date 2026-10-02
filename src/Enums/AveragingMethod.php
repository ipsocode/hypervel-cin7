<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a fixed asset type averages depreciation over a period.
 *
 * @see docs/data.md
 */
enum AveragingMethod: string
{
    case FullMonth = 'Full Month';
    case ActualDays = 'Actual Days';
}
