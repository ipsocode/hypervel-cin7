<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarData;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarDayData;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarPostData;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarPutData;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarSpecialDayData;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\GetProductionFactoryCalendar;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\PostProductionFactoryCalendar;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\PutProductionFactoryCalendar;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/factoryCalendar`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionFactoryCalendar::class => [
            GetProductionFactoryCalendar::class,
            [2024],
            Method::GET,
            '/ExternalApi/v2/production/factoryCalendar',
            ['Year' => 2024],
            null,
        ],
        PostProductionFactoryCalendar::class => [
            PostProductionFactoryCalendar::class,
            [['Year' => 2024, 'WeekStart' => 'Monday']],
            Method::POST,
            '/ExternalApi/v2/production/factoryCalendar',
            [],
            ['Year' => 2024, 'WeekStart' => 'Monday'],
        ],
        PutProductionFactoryCalendar::class => [
            PutProductionFactoryCalendar::class,
            [['Year' => 2024]],
            Method::PUT,
            '/ExternalApi/v2/production/factoryCalendar',
            [],
            ['Year' => 2024],
        ],
    ],
    'resources' => [
        'production factoryCalendar get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->factoryCalendar()->get(2024),
            GetProductionFactoryCalendar::class,
            Method::GET,
            '/ExternalApi/v2/production/factoryCalendar',
            ['Year' => 2024],
            null,
        ],
        'production factoryCalendar post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->factoryCalendar()->post(['Year' => 2024, 'WeekStart' => 'Monday']),
            PostProductionFactoryCalendar::class,
            Method::POST,
            '/ExternalApi/v2/production/factoryCalendar',
            [],
            ['Year' => 2024, 'WeekStart' => 'Monday'],
        ],
        'production factoryCalendar put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->factoryCalendar()->put(['Year' => 2024]),
            PutProductionFactoryCalendar::class,
            Method::PUT,
            '/ExternalApi/v2/production/factoryCalendar',
            [],
            ['Year' => 2024],
        ],
    ],
    'dtos' => [
        GetProductionFactoryCalendar::class => [GetProductionFactoryCalendar::class, [2024], Cin7Payloads::load('production/factoryCalendar', 'get.response'), FactoryCalendarData::class, ''],
        PostProductionFactoryCalendar::class => [PostProductionFactoryCalendar::class, [[]], Cin7Payloads::load('production/factoryCalendar', 'post.response'), FactoryCalendarData::class, ''],
        PutProductionFactoryCalendar::class => [PutProductionFactoryCalendar::class, [[]], Cin7Payloads::load('production/factoryCalendar', 'put.response'), FactoryCalendarData::class, ''],
    ],
    'bodies' => [
        'FactoryCalendarPostData production/factoryCalendar' => [FactoryCalendarPostData::class, Cin7Payloads::load('production/factoryCalendar', 'post.request')],
        'FactoryCalendarPutData production/factoryCalendar' => [FactoryCalendarPutData::class, Cin7Payloads::load('production/factoryCalendar', 'put.request')],
    ],
    'missing' => [
        'FactoryCalendarDayData without DayOfWeek' => [FactoryCalendarDayData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'get.response')['FactoryCalendarDays'][0], 'DayOfWeek')],
        'FactoryCalendarDayData without StartTime' => [FactoryCalendarDayData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'get.response')['FactoryCalendarDays'][0], 'StartTime')],
        'FactoryCalendarDayData without EndTime' => [FactoryCalendarDayData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'get.response')['FactoryCalendarDays'][0], 'EndTime')],
        'FactoryCalendarSpecialDayData without Date' => [FactoryCalendarSpecialDayData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'get.response')['FactoryCalendarSpecialDays'][0], 'Date')],
        'FactoryCalendarData without Year' => [FactoryCalendarData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'get.response'), 'Year')],
        'FactoryCalendarPostData without Year' => [FactoryCalendarPostData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'post.request'), 'Year')],
        'FactoryCalendarPostData without WeekStart' => [FactoryCalendarPostData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'post.request'), 'WeekStart')],
        'FactoryCalendarPostData without FactoryCalendarDays' => [FactoryCalendarPostData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'post.request'), 'FactoryCalendarDays')],
        'FactoryCalendarPutData without Year' => [FactoryCalendarPutData::class, Arr::except(Cin7Payloads::load('production/factoryCalendar', 'put.request'), 'Year')],
    ],
    'required' => [
        FactoryCalendarDayData::class => ['DayOfWeek', 'StartTime', 'EndTime'],
        FactoryCalendarSpecialDayData::class => ['Date'],
        FactoryCalendarData::class => ['Year'],
        FactoryCalendarPostData::class => ['Year', 'WeekStart', 'FactoryCalendarDays'],
        FactoryCalendarPutData::class => ['Year'],
    ],
];
