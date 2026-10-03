<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\FactoryCalendar;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\DayOfWeek;

/**
 * FactoryCalendar, the response of every `production/factoryCalendar` action, and the model of its
 * days: the week it starts on, its seven days and its special days. The bodies of POST and PUT are
 * `FactoryCalendarPostData` and `FactoryCalendarPutData`.
 *
 * @see docs/data.md
 */
final class FactoryCalendarData extends Data implements WithResponse
{
    use HasResponse;

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
