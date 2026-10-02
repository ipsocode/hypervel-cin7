<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferData;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferLineData;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferPostData;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferPutData;
use Ipsocode\Cin7\Requests\StockTransfer\DeleteStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\GetStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\PostStockTransfer;
use Ipsocode\Cin7\Requests\StockTransfer\PutStockTransfer;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockTransfer`; tests/Catalogue.php merges every file's rows by kind.

// The fields every stock transfer requires, the locations a write needs, and a line to send.
$fields = ['Status' => 'DRAFT', 'CompletionDate' => '2017-12-19T00:00:00', 'Lines' => [['SKU' => 'Bread', 'TransferQuantity' => 100]]];
$locations = ['FromLocation' => 'Main Warehouse', 'ToLocation' => 'Main Warehouse: Bin 1'];
$sent = [...$locations, 'Status' => 'DRAFT', 'CompletionDate' => '2017-12-19T00:00:00', 'Lines' => [['TransferQuantity' => 100.0, 'SKU' => 'Bread']]];
$taskId = 'd144d7c8-b3f8-4b43-9d64-d6ee948606a2';

return [
    'requests' => [
        GetStockTransfer::class => [
            GetStockTransfer::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/stockTransfer',
            ['TaskID' => $taskId],
            null,
        ],
        PostStockTransfer::class => [
            PostStockTransfer::class,
            [['Status' => 'DRAFT', 'Reference' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['Status' => 'DRAFT', 'Reference' => 'Test'],
        ],
        PutStockTransfer::class => [
            PutStockTransfer::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteStockTransfer::class => [
            DeleteStockTransfer::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/stockTransfer',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostStockTransfer::class . ' with data' => [
            PostStockTransfer::class,
            [fn (): StockTransferPostData => StockTransferPostData::from([...$fields, ...$locations])],
            Method::POST,
            '/ExternalApi/v2/stockTransfer',
            [],
            $sent,
        ],
        PutStockTransfer::class . ' with data' => [
            PutStockTransfer::class,
            [fn (): StockTransferPutData => StockTransferPutData::from([...$fields, ...$locations, 'TaskID' => $taskId])],
            Method::PUT,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['TaskID' => $taskId, ...$sent],
        ],
    ],
    'resources' => [
        'stockTransfer get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->get($taskId),
            GetStockTransfer::class,
            Method::GET,
            '/ExternalApi/v2/stockTransfer',
            ['TaskID' => $taskId],
            null,
        ],
        'stockTransfer post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->post(['Status' => 'DRAFT', 'Reference' => 'Test']),
            PostStockTransfer::class,
            Method::POST,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['Status' => 'DRAFT', 'Reference' => 'Test'],
        ],
        'stockTransfer post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->post(StockTransferPostData::from([...$fields, ...$locations])),
            PostStockTransfer::class,
            Method::POST,
            '/ExternalApi/v2/stockTransfer',
            [],
            $sent,
        ],
        'stockTransfer put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutStockTransfer::class,
            Method::PUT,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'stockTransfer put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->put(StockTransferPutData::from([...$fields, ...$locations, 'TaskID' => $taskId])),
            PutStockTransfer::class,
            Method::PUT,
            '/ExternalApi/v2/stockTransfer',
            [],
            ['TaskID' => $taskId, ...$sent],
        ],
        'stockTransfer delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->delete($taskId),
            DeleteStockTransfer::class,
            Method::DELETE,
            '/ExternalApi/v2/stockTransfer',
            ['ID' => $taskId],
            null,
        ],
        'stockTransfer delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->delete($taskId, void: false),
            DeleteStockTransfer::class,
            Method::DELETE,
            '/ExternalApi/v2/stockTransfer',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetStockTransfer::class => [GetStockTransfer::class, [$taskId], Cin7Payloads::load('stockTransfer', 'get.response'), StockTransferData::class, ''],
        PostStockTransfer::class => [PostStockTransfer::class, [[]], Cin7Payloads::load('stockTransfer', 'post.response'), StockTransferData::class, ''],
        PutStockTransfer::class => [PutStockTransfer::class, [[]], Cin7Payloads::load('stockTransfer', 'put.response'), StockTransferData::class, ''],
        DeleteStockTransfer::class => [DeleteStockTransfer::class, [$taskId], Cin7Payloads::load('stockTransfer', 'delete.response'), StockTransferData::class, ''],
    ],
    'bodies' => [
        StockTransferPostData::class => [StockTransferPostData::class, Cin7Payloads::load('stockTransfer', 'post.request')],
        StockTransferPutData::class => [StockTransferPutData::class, Cin7Payloads::load('stockTransfer', 'put.request')],
    ],
    'missing' => [
        'stock transfer POST without Status' => [StockTransferPostData::class, Arr::except(Cin7Payloads::load('stockTransfer', 'post.request'), 'Status')],
        'stock transfer POST without Lines' => [StockTransferPostData::class, Arr::except(Cin7Payloads::load('stockTransfer', 'post.request'), 'Lines')],
        'stock transfer PUT without TaskID' => [StockTransferPutData::class, Arr::except(Cin7Payloads::load('stockTransfer', 'put.request'), 'TaskID')],
        'stock transfer without CompletionDate' => [StockTransferData::class, Arr::except(Cin7Payloads::load('stockTransfer', 'get.response'), 'CompletionDate')],
        'stock transfer line without TransferQuantity' => [StockTransferLineData::class, Arr::except(Cin7Payloads::load('stockTransfer', 'post.request')['Lines'][0], 'TransferQuantity')],
    ],
    'required' => [
        StockTransferData::class => ['Status', 'CompletionDate', 'Lines'],
        StockTransferPostData::class => ['Status', 'CompletionDate', 'Lines'],
        StockTransferPutData::class => ['Status', 'CompletionDate', 'Lines', 'TaskID'],
        StockTransferLineData::class => ['TransferQuantity'],
    ],
];
