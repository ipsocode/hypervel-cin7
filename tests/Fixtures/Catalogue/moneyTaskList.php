<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Requests\MoneyTaskList\GetMoneyTaskList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `moneyTaskList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetMoneyTaskList::class => [
            GetMoneyTaskList::class,
            [['Status' => 'COMPLETED', 'TaskType' => 'Spend Money']],
            Method::GET,
            '/ExternalApi/v2/moneyTaskList',
            ['Status' => 'COMPLETED', 'TaskType' => 'Spend Money', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'moneyTaskList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTaskList()->get(['Status' => 'COMPLETED']),
            GetMoneyTaskList::class,
            Method::GET,
            '/ExternalApi/v2/moneyTaskList',
            ['Status' => 'COMPLETED', 'page' => 1, 'limit' => 100],
            null,
        ],
        'moneyTaskList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTaskList()->paginate()->current(),
            GetMoneyTaskList::class,
            Method::GET,
            '/ExternalApi/v2/moneyTaskList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetMoneyTaskList::class => [
            GetMoneyTaskList::class,
            [],
            Cin7Payloads::moneyTaskList(),
            MoneyTaskListData::class,
            'MoneyTasks',
        ],
    ],
];
