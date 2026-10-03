<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\FactoryCalendar;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * FactoryCalendarSpecialDay, a date of the calendar's year that differs from its week. `Capacity`
 * is read-only.
 *
 * @see docs/data.md
 */
final class FactoryCalendarSpecialDayData extends Data
{
    public function __construct(
        #[DateTime]
        public string $Date,
        public ?bool $IsWeekend = null,
        public ?bool $IsHoliday = null,
        public ?int $StartTime = null,
        public ?int $EndTime = null,
        public ?int $BreakStartTime = null,
        public ?int $BreakEndTime = null,
        public ?int $Capacity = null,
        #[Max(512)]
        public ?string $Comment = null,
    ) {
    }
}
