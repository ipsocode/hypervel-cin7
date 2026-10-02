<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderData;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderLineData;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderPostData;
use Ipsocode\Cin7\Requests\StockTransfer\Order\GetStockTransferOrder;
use Ipsocode\Cin7\Requests\StockTransfer\Order\PostStockTransferOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockTransfer/order`; tests/Catalogue.php merges every file's rows by kind.

$taskId = '76a1294b-cea1-4a67-bde7-97363a9cd718';
$fields = ['TaskID' => $taskId, 'Status' => 'AUTHORISED', 'Lines' => [['SKU' => 'GB3-White', 'TransferQuantity' => 1]]];
$sent = ['TaskID' => $taskId, 'Status' => 'AUTHORISED', 'Lines' => [['TransferQuantity' => 1.0, 'SKU' => 'GB3-White']]];

return [
    'requests' => [
        GetStockTransferOrder::class => [
            GetStockTransferOrder::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/stockTransfer/order',
            ['TaskID' => $taskId],
            null,
        ],
        PostStockTransferOrder::class => [
            PostStockTransferOrder::class,
            [['TaskID' => $taskId, 'Status' => 'DRAFT']],
            Method::POST,
            '/ExternalApi/v2/stockTransfer/order',
            [],
            ['TaskID' => $taskId, 'Status' => 'DRAFT'],
        ],
        PostStockTransferOrder::class . ' with data' => [
            PostStockTransferOrder::class,
            [fn (): StockTransferOrderPostData => StockTransferOrderPostData::from($fields)],
            Method::POST,
            '/ExternalApi/v2/stockTransfer/order',
            [],
            $sent,
        ],
    ],
    'resources' => [
        'stockTransfer order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->order()->get($taskId),
            GetStockTransferOrder::class,
            Method::GET,
            '/ExternalApi/v2/stockTransfer/order',
            ['TaskID' => $taskId],
            null,
        ],
        'stockTransfer order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->order()->post(['TaskID' => $taskId, 'Status' => 'DRAFT']),
            PostStockTransferOrder::class,
            Method::POST,
            '/ExternalApi/v2/stockTransfer/order',
            [],
            ['TaskID' => $taskId, 'Status' => 'DRAFT'],
        ],
        'stockTransfer order post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransfer()->order()->post(StockTransferOrderPostData::from($fields)),
            PostStockTransferOrder::class,
            Method::POST,
            '/ExternalApi/v2/stockTransfer/order',
            [],
            $sent,
        ],
    ],
    'dtos' => [
        GetStockTransferOrder::class => [GetStockTransferOrder::class, [$taskId], Cin7Payloads::load('stockTransfer/order', 'get.response'), StockTransferOrderData::class, ''],
        PostStockTransferOrder::class => [PostStockTransferOrder::class, [[]], Cin7Payloads::load('stockTransfer/order', 'post.response'), StockTransferOrderData::class, ''],
    ],
    'bodies' => [
        StockTransferOrderPostData::class => [StockTransferOrderPostData::class, Cin7Payloads::load('stockTransfer/order', 'post.request')],
    ],
    'missing' => [
        'stock transfer order POST without Status' => [StockTransferOrderPostData::class, Arr::except(Cin7Payloads::load('stockTransfer/order', 'post.request'), 'Status')],
        'stock transfer order POST without TaskID' => [StockTransferOrderPostData::class, Arr::except(Cin7Payloads::load('stockTransfer/order', 'post.request'), 'TaskID')],
        'stock transfer order without Status' => [StockTransferOrderData::class, Arr::except(Cin7Payloads::load('stockTransfer/order', 'get.response'), 'Status')],
        'stock transfer order line without TransferQuantity' => [StockTransferOrderLineData::class, Arr::except(Cin7Payloads::load('stockTransfer/order', 'post.request')['Lines'][0], 'TransferQuantity')],
    ],
    'required' => [
        StockTransferOrderData::class => ['Status'],
        StockTransferOrderPostData::class => ['TaskID', 'Status', 'Lines'],
        StockTransferOrderLineData::class => ['TransferQuantity'],
    ],
];
