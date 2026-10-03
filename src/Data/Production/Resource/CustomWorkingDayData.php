<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DayOfWeek;

/**
 * CustomWorkingDay, a working day of a resource's capacity at a location that differs from the
 * factory calendar. `StartTime` and `EndTime` are required when the day is not left to the
 * calendar, which a day cannot tell; the times are minutes, 0 to 1440; `Capacity` is read-only.
 *
 * @see docs/data.md
 */
final class CustomWorkingDayData extends Data
{
    public function __construct(
        public DayOfWeek $DayOfWeek,
        public ?int $StartTime = null,
        public ?int $EndTime = null,
        public ?int $BreakStartTime = null,
        public ?int $BreakEndTime = null,
        public ?int $Capacity = null,
    ) {
    }
}
