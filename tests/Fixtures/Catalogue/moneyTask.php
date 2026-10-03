<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskLineData;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskPostData;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskPutData;
use Ipsocode\Cin7\Requests\MoneyTask\DeleteMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\GetMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PostMoneyTask;
use Ipsocode\Cin7\Requests\MoneyTask\PutMoneyTask;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `moneyOperation`, the Money Task resource; tests/Catalogue.php merges every
// file's rows by kind.

// The fields every money task requires, and every money task line.
$fields = ['TaskType' => 'Receive Money', 'Status' => 'DRAFT', 'BankAccount' => '198489', 'Date' => '2018-01-17T00:00:00'];
$line = ['Name' => 'Bread', 'Quantity' => 3, 'TaxRuleName' => 'Tax Exempt', 'AccountCode' => '800', 'Total' => 6];

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
            [fn (): MoneyTaskPostData => MoneyTaskPostData::from([...$fields, 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['Lines' => [['Name' => 'Bread', 'Quantity' => 3.0, 'TaxRuleName' => 'Tax Exempt', 'AccountCode' => '800', 'Total' => 6.0]], ...$fields],
        ],
        PutMoneyTask::class . ' with data' => [
            PutMoneyTask::class,
            [fn (): MoneyTaskPutData => MoneyTaskPutData::from([...$fields, 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'COMPLETED'])],
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', ...$fields, 'Status' => 'COMPLETED'],
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
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->post(MoneyTaskPostData::from([...$fields, 'TaskType' => 'Spend Money', 'Note' => null])),
            PostMoneyTask::class,
            Method::POST,
            '/ExternalApi/v2/moneyOperation',
            [],
            [...$fields, 'TaskType' => 'Spend Money'],
        ],
        'moneyTask put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->moneyTask()->put(MoneyTaskPutData::from([...$fields, 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Status' => 'VOIDED'])),
            PutMoneyTask::class,
            Method::PUT,
            '/ExternalApi/v2/moneyOperation',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', ...$fields, 'Status' => 'VOIDED'],
        ],
    ],
    'bodies' => [
        MoneyTaskPostData::class => [MoneyTaskPostData::class, Cin7Payloads::load('moneyTask', 'post.request')],
        MoneyTaskPutData::class => [MoneyTaskPutData::class, Cin7Payloads::load('moneyTask', 'put.request')],
    ],
    'missing' => [
        'money task PUT without TaskID' => [MoneyTaskPutData::class, Arr::except(Cin7Payloads::load('moneyTask', 'put.request'), 'TaskID')],
        'money task without BankAccount' => [MoneyTaskData::class, Arr::except(Cin7Payloads::moneyTask(), 'BankAccount')],
        'money task line without Total' => [MoneyTaskLineData::class, Arr::except($line, 'Total')],
    ],
    'required' => [
        MoneyTaskData::class => ['TaskType', 'Status', 'BankAccount', 'Date'],
        MoneyTaskPostData::class => ['TaskType', 'Status', 'BankAccount', 'Date'],
        MoneyTaskPutData::class => ['TaskType', 'Status', 'BankAccount', 'Date', 'TaskID'],
        MoneyTaskLineData::class => ['Name', 'Quantity', 'TaxRuleName', 'AccountCode', 'Total'],
    ],
    'dtos' => [
        GetMoneyTask::class => [GetMoneyTask::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PostMoneyTask::class => [PostMoneyTask::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        PutMoneyTask::class => [PutMoneyTask::class, [[]], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
        DeleteMoneyTask::class => [DeleteMoneyTask::class, ['task-1'], Cin7Payloads::moneyTask(), MoneyTaskData::class, ''],
    ],
];
