<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Requests\MoneyOperation\DeleteMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\GetMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PostMoneyOperation;
use Ipsocode\Cin7\Requests\MoneyOperation\PutMoneyOperation;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `moneyOperation`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        DeleteMoneyOperation::class => [
            DeleteMoneyOperation::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88', ['Void' => true]],
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
        GetMoneyOperation::class => [
            GetMoneyOperation::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88'],
            Method::GET,
            '/ExternalApi/v2/moneyOperation',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            null,
        ],
        PostMoneyOperation::class => [
            PostMoneyOperation::class,
            [['TaskType' => 'Receive Money']],
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money'],
        ],
        PutMoneyOperation::class => [
            PutMoneyOperation::class,
            [['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
        PostMoneyOperation::class . ' with data' => [
            PostMoneyOperation::class,
            [fn (): MoneyTaskData => MoneyTaskData::from(['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3]]])],
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money', 'Lines' => [['Name' => 'Bread', 'Quantity' => 3.0]]],
        ],
        PutMoneyOperation::class . ' with data' => [
            PutMoneyOperation::class,
            [fn (): MoneyTaskData => MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'])],
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
    ],
    'resources' => [
        'moneyOperation get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->get('b039f19e-66f8-4309-a4b1-abf928303c88'),
            GetMoneyOperation::class,
            Method::GET,
            '/ExternalApi/v2/moneyOperation',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
            null,
        ],
        'moneyOperation post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->post(['TaskType' => 'Receive Money']),
            PostMoneyOperation::class,
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Receive Money'],
        ],
        'moneyOperation put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->put(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED']),
            PutMoneyOperation::class,
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'],
        ],
        'moneyOperation delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->delete('b039f19e-66f8-4309-a4b1-abf928303c88'),
            DeleteMoneyOperation::class,
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
            null,
        ],
        'moneyOperation delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
            DeleteMoneyOperation::class,
            Method::DELETE,
            '/ExternalApi/v2/moneyOperation',
            ['ID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
        'moneyOperation post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->post(MoneyTaskData::from(['TaskType' => 'Spend Money', 'Note' => null])),
            PostMoneyOperation::class,
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskType' => 'Spend Money'],
        ],
        'moneyOperation put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyOperation()->put(MoneyTaskData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'])),
            PutMoneyOperation::class,
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'],
        ],
    ],
    'dtos' => [
        GetMoneyOperation::class => [GetMoneyOperation::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PostMoneyOperation::class => [PostMoneyOperation::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PutMoneyOperation::class => [PutMoneyOperation::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        DeleteMoneyOperation::class => [DeleteMoneyOperation::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
    ],
];
