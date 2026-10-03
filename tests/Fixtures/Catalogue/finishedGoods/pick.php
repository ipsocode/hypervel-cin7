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

// The catalogue rows for `finishedGoods/pick`; tests/Catalogue.php merges every file's rows by kind.

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
    ],
    'resources' => [
        'finishedGoods pick get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->pick()->get($taskId),
            GetFinishedGoodsPick::class,
            Method::GET,
            '/ExternalApi/v2/finishedGoods/pick',
            ['TaskID' => $taskId],
            null,
        ],
        'finishedGoods pick post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->pick()->post(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PostFinishedGoodsPick::class,
            Method::POST,
            '/ExternalApi/v2/finishedGoods/pick',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
    ],
    'dtos' => [
        GetFinishedGoodsPick::class => [GetFinishedGoodsPick::class, [$taskId], Cin7Payloads::load('finishedGoods/pick', 'get.response'), FinishedGoodsPickData::class, ''],
        PostFinishedGoodsPick::class => [PostFinishedGoodsPick::class, [[]], Cin7Payloads::load('finishedGoods/pick', 'post.response'), FinishedGoodsPickData::class, ''],
    ],
    'bodies' => [
        FinishedGoodsPickData::class => [FinishedGoodsPickData::class, Cin7Payloads::load('finishedGoods/pick', 'post.request')],
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
