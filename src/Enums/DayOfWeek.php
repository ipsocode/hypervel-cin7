<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * A day of the week: a factory calendar's week start and the day of its days and of a resource's
 * working days.
 *
 * @see docs/data.md
 */
enum DayOfWeek: string
{
    case Monday = 'Monday';
    case Tuesday = 'Tuesday';
    case Wednesday = 'Wednesday';
    case Thursday = 'Thursday';
    case Friday = 'Friday';
    case Saturday = 'Saturday';
    case Sunday = 'Sunday';
}
