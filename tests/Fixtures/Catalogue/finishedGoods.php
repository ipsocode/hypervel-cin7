<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsData;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsPostData;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsPutData;
use Ipsocode\Cin7\Requests\FinishedGoods\DeleteFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\GetFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\PostFinishedGoods;
use Ipsocode\Cin7\Requests\FinishedGoods\PutFinishedGoods;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `finishedGoods`; tests/Catalogue.php merges every file's rows by kind.

$taskId = 'c1819dba-772c-4689-8032-0ee7acb4bd66';

return [
    'requests' => [
        GetFinishedGoods::class => [
            GetFinishedGoods::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/finishedGoods',
            ['TaskID' => $taskId],
            null,
        ],
        PostFinishedGoods::class => [
            PostFinishedGoods::class,
            [['Status' => 'DRAFT', 'Notes' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/finishedGoods',
            [],
            ['Status' => 'DRAFT', 'Notes' => 'Test'],
        ],
        PostFinishedGoods::class . ' with data' => [
            PostFinishedGoods::class,
            [fn (): FinishedGoodsPostData => FinishedGoodsPostData::from(Cin7Payloads::load('finishedGoods', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/finishedGoods',
            [],
            [
                'Status' => 'COMPLETED',
                'WIPAccount' => '715',
                'Account' => '715',
                'Quantity' => 1.0,
                'CompletionDate' => '2018-01-03T00:00:00',
                'ProductID' => '524c20a3-a8ec-44f2-9685-311f1f7d1498',
                'ProductCode' => 'Bread',
                'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                'Location' => 'Main Warehouse',
                'WIPDate' => '2018-01-03T00:00:00',
                'ProductName' => 'Baked Bread',
                'BinID' => '',
                'Bin' => '',
                'BatchSN' => 'FG-00001',
                'ExpiryDate' => '2018-01-11T00:00:00',
                'Notes' => '',
                'CustomField1' => 'Low',
                'CustomField2' => 'false',
                'CustomField3' => 'test',
                'CustomField4' => '',
                'CustomField5' => '',
                'CustomField6' => '',
                'CustomField7' => '',
                'CustomField8' => '',
                'CustomField9' => '',
                'CustomField10' => '',
            ],
        ],
        PutFinishedGoods::class => [
            PutFinishedGoods::class,
            [['ID' => $taskId, 'Notes' => 'Test']],
            Method::PUT,
            '/ExternalApi/v2/finishedGoods',
            [],
            ['ID' => $taskId, 'Notes' => 'Test'],
        ],
        PutFinishedGoods::class . ' with data' => [
            PutFinishedGoods::class,
            [fn (): FinishedGoodsPutData => FinishedGoodsPutData::from(Cin7Payloads::load('finishedGoods', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/finishedGoods',
            [],
            [
                'ID' => '6e56b9fe-4105-4c9e-8eab-170e9486000c',
                'WIPAccount' => '715',
                'Account' => '715',
                'Quantity' => 1.0,
                'CompletionDate' => '2018-01-03T00:00:00',
                'ProductID' => '524c20a3-a8ec-44f2-9685-311f1f7d1498',
                'ProductCode' => 'Bread',
                'ProductName' => 'Baked Bread',
                'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                'Location' => 'Main Warehouse',
                'BinID' => '',
                'Bin' => '',
                'WIPDate' => '2018-01-03T00:00:00',
                'BatchSN' => 'FG-00001',
                'ExpiryDate' => '2018-01-11T00:00:00',
                'Notes' => '',
                'CustomField1' => 'Low',
                'CustomField2' => 'false',
                'CustomField3' => 'test',
                'CustomField4' => '',
                'CustomField5' => '',
                'CustomField6' => '',
                'CustomField7' => '',
                'CustomField8' => '',
                'CustomField9' => '',
                'CustomField10' => '',
            ],
        ],
        DeleteFinishedGoods::class => [
            DeleteFinishedGoods::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/finishedGoods',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
    ],
    'resources' => [
        'finishedGoods get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->get($taskId),
            GetFinishedGoods::class,
        ],
        'finishedGoods post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->post(['Status' => 'DRAFT', 'Notes' => 'Test']),
            PostFinishedGoods::class,
        ],
        'finishedGoods put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->put(['ID' => $taskId, 'Notes' => 'Test']),
            PutFinishedGoods::class,
        ],
        'finishedGoods delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->delete($taskId),
            DeleteFinishedGoods::class,
            Method::DELETE,
            '/ExternalApi/v2/finishedGoods',
            ['ID' => $taskId],
            null,
        ],
        'finishedGoods delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoods()->delete($taskId, void: false),
            DeleteFinishedGoods::class,
            Method::DELETE,
            '/ExternalApi/v2/finishedGoods',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetFinishedGoods::class => [GetFinishedGoods::class, [$taskId], Cin7Payloads::load('finishedGoods', 'get.response'), FinishedGoodsData::class, ''],
        PostFinishedGoods::class => [PostFinishedGoods::class, [[]], Cin7Payloads::load('finishedGoods', 'post.response'), FinishedGoodsData::class, ''],
        PutFinishedGoods::class => [PutFinishedGoods::class, [[]], Cin7Payloads::load('finishedGoods', 'put.response'), FinishedGoodsData::class, ''],
        DeleteFinishedGoods::class => [DeleteFinishedGoods::class, [$taskId], Cin7Payloads::load('finishedGoods', 'delete.response'), FinishedGoodsData::class, ''],
    ],
    'bodies' => [
        FinishedGoodsPostData::class => [FinishedGoodsPostData::class, PostFinishedGoods::class, Cin7Payloads::load('finishedGoods', 'post.request')],
        FinishedGoodsPutData::class => [FinishedGoodsPutData::class, PutFinishedGoods::class, Cin7Payloads::load('finishedGoods', 'put.request')],
    ],
    'missing' => [
        'finished goods POST without Status' => [FinishedGoodsPostData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'post.request'), 'Status')],
        'finished goods POST without WIPAccount' => [FinishedGoodsPostData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'post.request'), 'WIPAccount')],
        'finished goods POST without Account' => [FinishedGoodsPostData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'post.request'), 'Account')],
        'finished goods POST without Quantity' => [FinishedGoodsPostData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'post.request'), 'Quantity')],
        'finished goods POST without CompletionDate' => [FinishedGoodsPostData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'post.request'), 'CompletionDate')],
        'finished goods PUT without ID' => [FinishedGoodsPutData::class, Arr::except(Cin7Payloads::load('finishedGoods', 'put.request'), 'ID')],
    ],
    'required' => [
        FinishedGoodsPostData::class => ['Status', 'WIPAccount', 'Account', 'Quantity', 'CompletionDate'],
        FinishedGoodsPutData::class => ['ID'],
    ],
];
