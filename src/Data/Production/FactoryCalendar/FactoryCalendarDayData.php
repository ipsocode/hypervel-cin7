<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\FactoryCalendar;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DayOfWeek;

/**
 * FactoryCalendarDay, one day of a factory calendar's week. `StartTime`, `EndTime` and the break
 * times are minutes, 0 to 1440; `Capacity` is read-only.
 *
 * @see docs/data.md
 */
final class FactoryCalendarDayData extends Data
{
    public function __construct(
        public DayOfWeek $DayOfWeek,
        public int $StartTime,
        public int $EndTime,
        public ?bool $IsWeekend = null,
        public ?int $BreakStartTime = null,
        public ?int $BreakEndTime = null,
        public ?int $Capacity = null,
    ) {
    }
}
