<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTake\IdNameData;
use Ipsocode\Cin7\Data\StockTake\StockTakeData;
use Ipsocode\Cin7\Data\StockTake\StockTakePostData;
use Ipsocode\Cin7\Data\StockTake\StockTakePutData;
use Ipsocode\Cin7\Enums\StockTakeStatus;
use Ipsocode\Cin7\Requests\StockTake\DeleteStockTake;
use Ipsocode\Cin7\Requests\StockTake\GetStockTake;
use Ipsocode\Cin7\Requests\StockTake\PostStockTake;
use Ipsocode\Cin7\Requests\StockTake\PutStockTake;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stocktake`; tests/Catalogue.php merges every file's rows by kind.

// The fields every stock take requires.
$fields = ['EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403', 'Location' => 'Main Warehouse'];
$taskId = '328c0f87-d87f-4f87-b503-251e820f4b3c';

return [
    'requests' => [
        GetStockTake::class => [
            GetStockTake::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/stocktake',
            ['TaskID' => $taskId],
            null,
        ],
        PostStockTake::class => [
            PostStockTake::class,
            [['Account' => '403', 'Reference' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/stocktake',
            [],
            ['Account' => '403', 'Reference' => 'Test'],
        ],
        PutStockTake::class => [
            PutStockTake::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/stocktake',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteStockTake::class => [
            DeleteStockTake::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/stocktake',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostStockTake::class . ' with data' => [
            PostStockTake::class,
            [fn (): StockTakePostData => StockTakePostData::from([...$fields, 'Tags' => ['bread'], 'Categories' => [['ID' => 'ae180bfb-802f-4d5a-9a04-af3aebfc3d4a', 'Name' => 'Other']]])],
            Method::POST,
            '/ExternalApi/v2/stocktake',
            [],
            ['Location' => 'Main Warehouse', 'Tags' => ['bread'], 'Categories' => [['ID' => 'ae180bfb-802f-4d5a-9a04-af3aebfc3d4a', 'Name' => 'Other']], 'EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403'],
        ],
        PutStockTake::class . ' with data' => [
            PutStockTake::class,
            [fn (): StockTakePutData => StockTakePutData::from([...$fields, 'TaskID' => $taskId, 'Status' => 'IN PROGRESS', 'UseRelativeQuantity' => true])],
            Method::PUT,
            '/ExternalApi/v2/stocktake',
            [],
            ['TaskID' => $taskId, 'Status' => 'IN PROGRESS', 'Location' => 'Main Warehouse', 'UseRelativeQuantity' => true, 'EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403'],
        ],
    ],
    'resources' => [
        'stockTake get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->get($taskId),
            GetStockTake::class,
            Method::GET,
            '/ExternalApi/v2/stocktake',
            ['TaskID' => $taskId],
            null,
        ],
        'stockTake post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->post(['Account' => '403', 'Reference' => 'Test']),
            PostStockTake::class,
            Method::POST,
            '/ExternalApi/v2/stocktake',
            [],
            ['Account' => '403', 'Reference' => 'Test'],
        ],
        'stockTake post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->post(StockTakePostData::from($fields)),
            PostStockTake::class,
            Method::POST,
            '/ExternalApi/v2/stocktake',
            [],
            ['Location' => 'Main Warehouse', 'EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403'],
        ],
        'stockTake put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutStockTake::class,
            Method::PUT,
            '/ExternalApi/v2/stocktake',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'stockTake put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->put(StockTakePutData::from([...$fields, 'TaskID' => $taskId, 'Status' => StockTakeStatus::Completed])),
            PutStockTake::class,
            Method::PUT,
            '/ExternalApi/v2/stocktake',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED', 'Location' => 'Main Warehouse', 'EffectiveDate' => '2018-04-27T00:00:00', 'Account' => '403'],
        ],
        'stockTake delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->delete($taskId),
            DeleteStockTake::class,
            Method::DELETE,
            '/ExternalApi/v2/stocktake',
            ['ID' => $taskId],
            null,
        ],
        'stockTake delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTake()->delete($taskId, void: false),
            DeleteStockTake::class,
            Method::DELETE,
            '/ExternalApi/v2/stocktake',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetStockTake::class => [GetStockTake::class, [$taskId], Cin7Payloads::load('stocktake', 'get.response'), StockTakeData::class, ''],
        PostStockTake::class => [PostStockTake::class, [[]], Cin7Payloads::load('stocktake', 'post.response'), StockTakeData::class, ''],
        PutStockTake::class => [PutStockTake::class, [[]], Cin7Payloads::load('stocktake', 'put.response'), StockTakeData::class, ''],
        DeleteStockTake::class => [DeleteStockTake::class, [$taskId], Cin7Payloads::load('stocktake', 'delete.response'), StockTakeData::class, ''],
    ],
    'bodies' => [
        StockTakePostData::class => [StockTakePostData::class, Cin7Payloads::load('stocktake', 'post.request')],
        StockTakePutData::class => [StockTakePutData::class, Cin7Payloads::load('stocktake', 'put.request')],
    ],
    'missing' => [
        'stock take POST without Account' => [StockTakePostData::class, Arr::except(Cin7Payloads::load('stocktake', 'post.request'), 'Account')],
        'stock take PUT without Status' => [StockTakePutData::class, Arr::except(Cin7Payloads::load('stocktake', 'put.request'), 'Status')],
        'stock take PUT without TaskID' => [StockTakePutData::class, Arr::except(Cin7Payloads::load('stocktake', 'put.request'), 'TaskID')],
        'stock take without EffectiveDate' => [StockTakeData::class, Arr::except(Cin7Payloads::load('stocktake', 'get.response'), 'EffectiveDate')],
    ],
    'required' => [
        StockTakeData::class => ['EffectiveDate', 'Account'],
        StockTakePostData::class => ['EffectiveDate', 'Account'],
        StockTakePutData::class => ['EffectiveDate', 'Account', 'TaskID', 'Status'],
        IdNameData::class => [],
    ],
];
