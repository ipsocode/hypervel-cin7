<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a payment term counts its duration.
 *
 * @see docs/data.md
 */
enum PaymentTermMethod: string
{
    case NumberOfDays = 'number of days';
    case DayOfNextMonth = 'day of next month';
    case LastDayOfNextMonth = 'last day of next month';
    case DaysSinceTheEndOfTheMonth = 'days since the end of the month';
}
