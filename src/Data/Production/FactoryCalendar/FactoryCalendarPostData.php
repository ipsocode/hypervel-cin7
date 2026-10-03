<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\FactoryCalendar;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\DayOfWeek;

/**
 * The body of `production/factoryCalendar` POST: the FactoryCalendar table, which requires `Year`,
 * and for POST `WeekStart` and `FactoryCalendarDays`, one per day of the week (seven). The PUT
 * body is `FactoryCalendarPutData`.
 *
 * @see docs/data.md
 */
final class FactoryCalendarPostData extends Data
{
    /**
     * @param list<FactoryCalendarDayData> $FactoryCalendarDays
     * @param null|list<FactoryCalendarSpecialDayData> $FactoryCalendarSpecialDays
     */
    public function __construct(
        public int $Year,
        public DayOfWeek $WeekStart,
        #[DataCollectionOf(FactoryCalendarDayData::class)]
        public array $FactoryCalendarDays,
        #[DataCollectionOf(FactoryCalendarSpecialDayData::class)]
        public ?array $FactoryCalendarSpecialDays = null,
    ) {
    }
}
