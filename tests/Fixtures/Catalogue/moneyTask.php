<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Requests\MoneyTask\DeleteMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\GetMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PostMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PutMoneyTask;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `moneyOperation`, the Money Task resource; tests/Catalogue.php merges every
// file's rows by kind.

return [
    'requests' => [
        DeleteMoneyTask::class => [
            DeleteMoneyTask::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
        GetMoneyTask::class => [
            GetMoneyTask::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88'],
            Method::GET,
            '/ExternalApi/v2/moneyOperation',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            null,
        ],
        PostMoneyTask::class => [
            PostMoneyTask::class,
            [['TaskType' => 'Receive Money']],
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money'],
        ],
        PutMoneyTask::class => [
            PutMoneyTask::class,
            [['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
        PostMoneyTask::class . ' with data' => [
            PostMoneyTask::class,
            [fn (): MoneyTaskData => MoneyTaskData::from(['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3]]])],
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3.0]]],
        ],
        PutMoneyTask::class . ' with data' => [
            PutMoneyTask::class,
            [fn (): MoneyTaskData => MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'])],
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
    ],
    'resources' => [
        'moneyTask get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->get('b039f19e-66f8-4309-a4b1-abf928303c88'),
            GetMoneyTask::class,
            Method::GET,
            '/ExternalApi/v2/moneyOperation',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            null,
        ],
        'moneyTask post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->post(['TaskType' => 'Receive Money']),
            PostMoneyTask::class,
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money'],
        ],
        'moneyTask put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->put(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']),
            PutMoneyTask::class,
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
        'moneyTask delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->delete('b039f19e-66f8-4309-a4b1-abf928303c88'),
            DeleteMoneyTask::class,
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            null,
        ],
        'moneyTask delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
            DeleteMoneyTask::class,
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
        'moneyTask post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->post(MoneyTaskData::from(['TaskType' => 'Spend Money', 'Note' => null])),
            PostMoneyTask::class,
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Spend Money'],
        ],
        'moneyTask put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->put(MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'])),
            PutMoneyTask::class,
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'],
        ],
    ],
    'dtos' => [
        GetMoneyTask::class => [GetMoneyTask::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PostMoneyTask::class => [PostMoneyTask::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PutMoneyTask::class => [PutMoneyTask::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        DeleteMoneyTask::class => [DeleteMoneyTask::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
    ],
];
