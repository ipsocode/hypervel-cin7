<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffData;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffLineData;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffPostData;
use Ipsocode\Cin7\Data\InventoryWriteOff\InventoryWriteOffPutData;
use Ipsocode\Cin7\Requests\InventoryWriteOff\DeleteInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\GetInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\PostInventoryWriteOff;
use Ipsocode\Cin7\Requests\InventoryWriteOff\PutInventoryWriteOff;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `inventoryWriteOff`; tests/Catalogue.php merges every file's rows by kind.

// The fields every write-off requires, and the location a write needs.
$fields = ['Status' => 'DRAFT', 'Account' => '404', 'Location' => 'Main Warehouse'];
$sent = ['Location' => 'Main Warehouse', 'Status' => 'DRAFT', 'Account' => '404'];
$taskId = 'bf0cd3c6-a525-4105-a5a9-067bf4dbb43e';

return [
    'requests' => [
        GetInventoryWriteOff::class => [
            GetInventoryWriteOff::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/inventoryWriteOff',
            ['TaskID' => $taskId],
            null,
        ],
        PostInventoryWriteOff::class => [
            PostInventoryWriteOff::class,
            [['Status' => 'DRAFT', 'Notes' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['Status' => 'DRAFT', 'Notes' => 'Test'],
        ],
        PutInventoryWriteOff::class => [
            PutInventoryWriteOff::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteInventoryWriteOff::class => [
            DeleteInventoryWriteOff::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/inventoryWriteOff',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostInventoryWriteOff::class . ' with data' => [
            PostInventoryWriteOff::class,
            [fn (): InventoryWriteOffPostData => InventoryWriteOffPostData::from([...$fields, 'Notes' => 'Test', 'Lines' => [['ProductCode' => 'Bread', 'Quantity' => 2]]])],
            Method::POST,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['Location' => 'Main Warehouse', 'Notes' => 'Test', 'Lines' => [['Quantity' => 2.0, 'ProductCode' => 'Bread']], 'Status' => 'DRAFT', 'Account' => '404'],
        ],
        PutInventoryWriteOff::class . ' with data' => [
            PutInventoryWriteOff::class,
            [fn (): InventoryWriteOffPutData => InventoryWriteOffPutData::from([...$fields, 'TaskID' => $taskId])],
            Method::PUT,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['TaskID' => $taskId, ...$sent],
        ],
    ],
    'resources' => [
        'inventoryWriteOff get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->get($taskId),
            GetInventoryWriteOff::class,
            Method::GET,
            '/ExternalApi/v2/inventoryWriteOff',
            ['TaskID' => $taskId],
            null,
        ],
        'inventoryWriteOff post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->post(['Status' => 'DRAFT', 'Notes' => 'Test']),
            PostInventoryWriteOff::class,
            Method::POST,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['Status' => 'DRAFT', 'Notes' => 'Test'],
        ],
        'inventoryWriteOff post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->post(InventoryWriteOffPostData::from($fields)),
            PostInventoryWriteOff::class,
            Method::POST,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            $sent,
        ],
        'inventoryWriteOff put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutInventoryWriteOff::class,
            Method::PUT,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'inventoryWriteOff put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->put(InventoryWriteOffPutData::from([...$fields, 'TaskID' => $taskId])),
            PutInventoryWriteOff::class,
            Method::PUT,
            '/ExternalApi/v2/inventoryWriteOff',
            [],
            ['TaskID' => $taskId, ...$sent],
        ],
        'inventoryWriteOff delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->delete($taskId),
            DeleteInventoryWriteOff::class,
            Method::DELETE,
            '/ExternalApi/v2/inventoryWriteOff',
            ['ID' => $taskId],
            null,
        ],
        'inventoryWriteOff delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOff()->delete($taskId, void: false),
            DeleteInventoryWriteOff::class,
            Method::DELETE,
            '/ExternalApi/v2/inventoryWriteOff',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetInventoryWriteOff::class => [GetInventoryWriteOff::class, [$taskId], Cin7Payloads::load('inventoryWriteOff', 'get.response'), InventoryWriteOffData::class, ''],
        PostInventoryWriteOff::class => [PostInventoryWriteOff::class, [[]], Cin7Payloads::load('inventoryWriteOff', 'post.response'), InventoryWriteOffData::class, ''],
        PutInventoryWriteOff::class => [PutInventoryWriteOff::class, [[]], Cin7Payloads::load('inventoryWriteOff', 'put.response'), InventoryWriteOffData::class, ''],
        DeleteInventoryWriteOff::class => [DeleteInventoryWriteOff::class, [$taskId], Cin7Payloads::load('inventoryWriteOff', 'delete.response'), InventoryWriteOffData::class, ''],
    ],
    'bodies' => [
        InventoryWriteOffPostData::class => [InventoryWriteOffPostData::class, Cin7Payloads::load('inventoryWriteOff', 'post.request')],
        InventoryWriteOffPutData::class => [InventoryWriteOffPutData::class, Cin7Payloads::load('inventoryWriteOff', 'put.request')],
    ],
    'missing' => [
        'inventory write-off POST without Status' => [InventoryWriteOffPostData::class, Arr::except(Cin7Payloads::load('inventoryWriteOff', 'post.request'), 'Status')],
        'inventory write-off POST without Account' => [InventoryWriteOffPostData::class, Arr::except(Cin7Payloads::load('inventoryWriteOff', 'post.request'), 'Account')],
        'inventory write-off PUT without TaskID' => [InventoryWriteOffPutData::class, Arr::except(Cin7Payloads::load('inventoryWriteOff', 'put.request'), 'TaskID')],
        'inventory write-off without Account' => [InventoryWriteOffData::class, Arr::except(Cin7Payloads::load('inventoryWriteOff', 'get.response'), 'Account')],
        'inventory write-off line without Quantity' => [InventoryWriteOffLineData::class, Arr::except(Cin7Payloads::load('inventoryWriteOff', 'post.request')['Lines'][0], 'Quantity')],
    ],
    'required' => [
        InventoryWriteOffData::class => ['Status', 'Account'],
        InventoryWriteOffPostData::class => ['Status', 'Account'],
        InventoryWriteOffPutData::class => ['Status', 'Account', 'TaskID'],
        InventoryWriteOffLineData::class => ['Quantity'],
    ],
];
