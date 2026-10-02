<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\ExistingStockLineData;
use Ipsocode\Cin7\Data\Other\NewStockLineData;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentData;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentPostData;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentPutData;
use Ipsocode\Cin7\Requests\StockAdjustment\DeleteStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\GetStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\PostStockAdjustment;
use Ipsocode\Cin7\Requests\StockAdjustment\PutStockAdjustment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockadjustment`; tests/Catalogue.php merges every file's rows by kind.

// The fields every stock adjustment requires, and a line it can be sent with.
$fields = ['EffectiveDate' => '2017-12-01T00:00:00', 'Status' => 'DRAFT'];
$line = ['SKU' => 'AF308', 'Quantity' => 600.0, 'UnitCost' => 1.0, 'Location' => 'Main Warehouse'];
$sent = ['Quantity' => 600.0, 'UnitCost' => 1.0, 'SKU' => 'AF308', 'Location' => 'Main Warehouse'];
$taskId = '107e8ba9-418c-4233-bf80-369036867144';

return [
    'requests' => [
        GetStockAdjustment::class => [
            GetStockAdjustment::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/stockadjustment',
            ['TaskID' => $taskId],
            null,
        ],
        PostStockAdjustment::class => [
            PostStockAdjustment::class,
            [['Status' => 'DRAFT', 'Comment' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Status' => 'DRAFT', 'Comment' => 'Test'],
        ],
        PutStockAdjustment::class => [
            PutStockAdjustment::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteStockAdjustment::class => [
            DeleteStockAdjustment::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/stockadjustment',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostStockAdjustment::class . ' with data' => [
            PostStockAdjustment::class,
            [fn (): StockAdjustmentPostData => StockAdjustmentPostData::from([...$fields, 'Lines' => [$line], 'UpdateOnHand' => true, 'Comment' => 'Test'])],
            Method::POST,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Lines' => [$sent], 'UpdateOnHand' => true, 'Comment' => 'Test', ...$fields],
        ],
        PutStockAdjustment::class . ' with data' => [
            PutStockAdjustment::class,
            [fn (): StockAdjustmentPutData => StockAdjustmentPutData::from([...$fields, 'Lines' => [$line], 'TaskID' => $taskId])],
            Method::PUT,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Lines' => [$sent], 'TaskID' => $taskId, ...$fields],
        ],
    ],
    'resources' => [
        'stockAdjustment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->get($taskId),
            GetStockAdjustment::class,
            Method::GET,
            '/ExternalApi/v2/stockadjustment',
            ['TaskID' => $taskId],
            null,
        ],
        'stockAdjustment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->post(['Status' => 'DRAFT', 'Comment' => 'Test']),
            PostStockAdjustment::class,
            Method::POST,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Status' => 'DRAFT', 'Comment' => 'Test'],
        ],
        'stockAdjustment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->post(StockAdjustmentPostData::from([...$fields, 'Lines' => [$line]])),
            PostStockAdjustment::class,
            Method::POST,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Lines' => [$sent], ...$fields],
        ],
        'stockAdjustment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutStockAdjustment::class,
            Method::PUT,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'stockAdjustment put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->put(StockAdjustmentPutData::from([...$fields, 'Lines' => [$line], 'TaskID' => $taskId])),
            PutStockAdjustment::class,
            Method::PUT,
            '/ExternalApi/v2/stockadjustment',
            [],
            ['Lines' => [$sent], 'TaskID' => $taskId, ...$fields],
        ],
        'stockAdjustment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->delete($taskId),
            DeleteStockAdjustment::class,
            Method::DELETE,
            '/ExternalApi/v2/stockadjustment',
            ['ID' => $taskId],
            null,
        ],
        'stockAdjustment delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustment()->delete($taskId, void: false),
            DeleteStockAdjustment::class,
            Method::DELETE,
            '/ExternalApi/v2/stockadjustment',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetStockAdjustment::class => [GetStockAdjustment::class, [$taskId], Cin7Payloads::load('stockadjustment', 'get.response'), StockAdjustmentData::class, ''],
        PostStockAdjustment::class => [PostStockAdjustment::class, [[]], Cin7Payloads::load('stockadjustment', 'post.response'), StockAdjustmentData::class, ''],
        PutStockAdjustment::class => [PutStockAdjustment::class, [[]], Cin7Payloads::load('stockadjustment', 'put.response'), StockAdjustmentData::class, ''],
        DeleteStockAdjustment::class => [DeleteStockAdjustment::class, [$taskId], Cin7Payloads::load('stockadjustment', 'delete.response'), StockAdjustmentData::class, ''],
    ],
    'bodies' => [
        StockAdjustmentPostData::class => [StockAdjustmentPostData::class, Cin7Payloads::load('stockadjustment', 'post.request')],
        StockAdjustmentPutData::class => [StockAdjustmentPutData::class, Cin7Payloads::load('stockadjustment', 'put.request')],
    ],
    'missing' => [
        'stock adjustment POST without Status' => [StockAdjustmentPostData::class, Arr::except(Cin7Payloads::load('stockadjustment', 'post.request'), 'Status')],
        'stock adjustment POST without Lines' => [StockAdjustmentPostData::class, Arr::except(Cin7Payloads::load('stockadjustment', 'post.request'), 'Lines')],
        'stock adjustment PUT without TaskID' => [StockAdjustmentPutData::class, Arr::except(Cin7Payloads::load('stockadjustment', 'put.request'), 'TaskID')],
        'stock adjustment without EffectiveDate' => [StockAdjustmentData::class, Arr::except(Cin7Payloads::load('stockadjustment', 'get.response'), 'EffectiveDate')],
        'new stock line without UnitCost' => [NewStockLineData::class, Arr::except(Cin7Payloads::load('stockadjustment', 'post.request')['Lines'][0], 'UnitCost')],
    ],
    'required' => [
        StockAdjustmentData::class => ['EffectiveDate', 'Status'],
        StockAdjustmentPostData::class => ['EffectiveDate', 'Status', 'Lines'],
        StockAdjustmentPutData::class => ['EffectiveDate', 'Status', 'Lines', 'TaskID'],
        NewStockLineData::class => ['Quantity', 'UnitCost'],
        ExistingStockLineData::class => [],
    ],
];
