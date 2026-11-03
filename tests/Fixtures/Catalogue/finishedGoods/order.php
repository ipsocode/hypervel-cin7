<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderData;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderLineData;
use Ipsocode\Cin7\Requests\FinishedGoods\Order\GetFinishedGoodsOrder;
use Ipsocode\Cin7\Requests\FinishedGoods\Order\PostFinishedGoodsOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `finishedGoods/order`; tests/Catalogue.php merges every file's rows by kind.

$taskId = 'dcede9f5-58ba-4236-81fe-b3d43890b2dc';

return [
    'requests' => [
        GetFinishedGoodsOrder::class => [
            GetFinishedGoodsOrder::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/finishedGoods/order',
            ['TaskID' => $taskId],
            null,
        ],
        PostFinishedGoodsOrder::class => [
            PostFinishedGoodsOrder::class,
            [['TaskID' => $taskId, 'Status' => 'DRAFT']],
            Method::POST,
            '/ExternalApi/v2/finishedGoods/order',
            [],
            ['TaskID' => $taskId, 'Status' => 'DRAFT'],
        ],
        PostFinishedGoodsOrder::class . ' with data' => [
            PostFinishedGoodsOrder::class,
            [fn (): FinishedGoodsOrderData => FinishedGoodsOrderData::from(Cin7Payloads::load('finishedGoods/order', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/finishedGoods/order',
            [],
            [
                'TaskID' => 'dcede9f5-58ba-4236-81fe-b3d43890b2dc',
                'Status' => 'AUTHORISED',
                'OrderLines' => [
                    [
                        'Quantity' => 1.0,
                        'ProductID' => 'b05c1c28-a125-4178-bb1b-ba200955e9f5',
                        'ProductCode' => 'BOOK',
                        'Name' => 'Fish out of Water: Finding Your Brand',
                        'ExpenseAccount' => '',
                        'Unit' => '',
                        'WastagePercent' => 0.0,
                        'WastageQuantity' => 0.0,
                        'TotalQuantity' => 1.0,
                        'TotalCost' => 0.5,
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'finishedGoods order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->order()->get($taskId),
            GetFinishedGoodsOrder::class,
        ],
        'finishedGoods order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->order()->post(['TaskID' => $taskId, 'Status' => 'DRAFT']),
            PostFinishedGoodsOrder::class,
        ],
    ],
    'dtos' => [
        GetFinishedGoodsOrder::class => [GetFinishedGoodsOrder::class, [$taskId], Cin7Payloads::load('finishedGoods/order', 'get.response'), FinishedGoodsOrderData::class, ''],
        PostFinishedGoodsOrder::class => [PostFinishedGoodsOrder::class, [[]], Cin7Payloads::load('finishedGoods/order', 'post.response'), FinishedGoodsOrderData::class, ''],
    ],
    'bodies' => [
        FinishedGoodsOrderData::class => [FinishedGoodsOrderData::class, PostFinishedGoodsOrder::class, Cin7Payloads::load('finishedGoods/order', 'post.request')],
    ],
    'missing' => [
        'finished goods order line without Quantity' => [FinishedGoodsOrderLineData::class, Arr::except(Cin7Payloads::load('finishedGoods/order', 'post.request')['OrderLines'][0], 'Quantity')],
    ],
    'required' => [
        FinishedGoodsOrderLineData::class => ['Quantity'],
    ],
];
