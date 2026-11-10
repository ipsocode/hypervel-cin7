<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickData;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickLineData;
use Ipsocode\Cin7\Requests\FinishedGoods\Pick\GetFinishedGoodsPick;
use Ipsocode\Cin7\Requests\FinishedGoods\Pick\PostFinishedGoodsPick;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `finishedGoods/pick`; tests/Catalogue.php merges every file's rows by kind.

$taskId = 'dcede9f5-58ba-4236-81fe-b3d43890b2dc';

return [
    'requests' => [
        GetFinishedGoodsPick::class => [
            GetFinishedGoodsPick::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/finishedGoods/pick',
            ['TaskID' => $taskId],
            null,
        ],
        PostFinishedGoodsPick::class => [
            PostFinishedGoodsPick::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::POST,
            '/ExternalApi/v2/finishedGoods/pick',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        PostFinishedGoodsPick::class . ' with data' => [
            PostFinishedGoodsPick::class,
            [fn (): FinishedGoodsPickData => FinishedGoodsPickData::from(Cin7Payloads::load('finishedGoods/pick', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/finishedGoods/pick',
            [],
            [
                'CompletionDate' => '2019-02-16T00:00:00',
                'TaskID' => 'dcede9f5-58ba-4236-81fe-b3d43890b2dc',
                'Status' => 'COMPLETED',
                'WIPAccount' => '715',
                'WIPDate' => '2019-02-15T00:00:00',
                'Account' => '714',
                'PickLines' => [
                    [
                        'Unit' => 'Item',
                        'Cost' => 0.0,
                        'ProductID' => 'e1cb8393-349d-41e6-b317-c42f21c543c6',
                        'ProductCode' => 'Assembly 01',
                        'Name' => 'Assembly name 01',
                        'Quantity' => 30.0,
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'finishedGoods pick get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->pick()->get($taskId),
            GetFinishedGoodsPick::class,
        ],
        'finishedGoods pick post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->pick()->post(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PostFinishedGoodsPick::class,
        ],
    ],
    'dtos' => [
        GetFinishedGoodsPick::class => [GetFinishedGoodsPick::class, [$taskId], Cin7Payloads::load('finishedGoods/pick', 'get.response'), FinishedGoodsPickData::class, ''],
        PostFinishedGoodsPick::class => [PostFinishedGoodsPick::class, [[]], Cin7Payloads::load('finishedGoods/pick', 'post.response'), FinishedGoodsPickData::class, ''],
    ],
    'bodies' => [
        FinishedGoodsPickData::class => [FinishedGoodsPickData::class, PostFinishedGoodsPick::class, Cin7Payloads::load('finishedGoods/pick', 'post.request')],
    ],
    'missing' => [
        'finished goods pick POST without CompletionDate' => [FinishedGoodsPickData::class, Arr::except(Cin7Payloads::load('finishedGoods/pick', 'post.request'), 'CompletionDate')],
        'finished goods pick line without Quantity' => [FinishedGoodsPickLineData::class, Arr::except(Cin7Payloads::load('finishedGoods/pick', 'post.request')['PickLines'][0], 'Quantity')],
    ],
    'required' => [
        FinishedGoodsPickData::class => ['CompletionDate'],
        FinishedGoodsPickLineData::class => ['Quantity'],
    ],
];
