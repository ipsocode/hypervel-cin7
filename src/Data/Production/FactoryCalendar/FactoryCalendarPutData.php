<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\FactoryCalendar;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DayOfWeek;

/**
 * The body of `production/factoryCalendar` PUT: the FactoryCalendar table, which requires only the
 * `Year`; a `FactoryCalendarSpecialDays` sent empty deletes the special days. The POST body is
 * `FactoryCalendarPostData`.
 *
 * @see docs/data.md
 */
final class FactoryCalendarPutData extends Data
{
    /**
     * @param null|list<FactoryCalendarDayData> $FactoryCalendarDays
     * @param null|list<FactoryCalendarSpecialDayData> $FactoryCalendarSpecialDays
     */
    public function __construct(
        public int $Year,
        public ?DayOfWeek $WeekStart = null,
        #[DataCollectionOf(FactoryCalendarDayData::class)]
        public ?array $FactoryCalendarDays = null,
        #[DataCollectionOf(FactoryCalendarSpecialDayData::class)]
        public ?array $FactoryCalendarSpecialDays = null,
    ) {
    }
}
