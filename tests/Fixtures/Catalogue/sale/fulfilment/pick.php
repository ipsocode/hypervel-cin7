<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPutData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\GetSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PostSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PutSaleFulfilmentPick;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/fulfilment/pick`; tests/Catalogue.php merges every file's rows by kind.

$line = ['SKU' => 'Bread', 'Quantity' => 1, 'Location' => 'Main Warehouse'];
$lineSent = ['SKU' => 'Bread', 'Quantity' => 1.0, 'Location' => 'Main Warehouse'];

return [
    'requests' => [
        GetSaleFulfilmentPick::class => [
            GetSaleFulfilmentPick::class,
            ['cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'includeProductInfo' => true],
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/pick',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'IncludeProductInfo' => 'true'],
            null,
        ],
        PostSaleFulfilmentPick::class => [
            PostSaleFulfilmentPick::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK']],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'],
        ],
        PutSaleFulfilmentPick::class => [
            PutSaleFulfilmentPick::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        PostSaleFulfilmentPick::class . ' with data' => [
            PostSaleFulfilmentPick::class,
            [fn (): SaleFulfilmentPickPostData => SaleFulfilmentPickPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'])],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['AutoPickMode' => 'AUTOPICK', 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
        PutSaleFulfilmentPick::class . ' with data' => [
            PutSaleFulfilmentPick::class,
            [fn (): SaleFulfilmentPickPutData => SaleFulfilmentPickPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['Status' => 'DRAFT', 'Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
    ],
    'resources' => [
        'sale fulfilment pick get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pick()->get('cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'),
            GetSaleFulfilmentPick::class,
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/pick',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
            null,
        ],
        'sale fulfilment pick post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pick()->post(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK']),
            PostSaleFulfilmentPick::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'],
        ],
        'sale fulfilment pick post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pick()->post(SaleFulfilmentPickPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED', 'Lines' => [$line]])),
            PostSaleFulfilmentPick::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['Status' => 'AUTHORISED', 'Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
        'sale fulfilment pick put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pick()->put(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']),
            PutSaleFulfilmentPick::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        'sale fulfilment pick put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pick()->put(SaleFulfilmentPickPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'AutoPickMode' => 'AUTOPICK'])),
            PutSaleFulfilmentPick::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pick',
            [],
            ['AutoPickMode' => 'AUTOPICK', 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
    ],
    'dtos' => [
        GetSaleFulfilmentPick::class => [GetSaleFulfilmentPick::class, ['task-1'], Cin7Payloads::load('sale/fulfilment/pick', 'get.response'), SaleFulfilmentPickData::class, ''],
        PostSaleFulfilmentPick::class => [PostSaleFulfilmentPick::class, [[]], Cin7Payloads::load('sale/fulfilment/pick', 'get.response'), SaleFulfilmentPickData::class, ''],
        PutSaleFulfilmentPick::class => [PutSaleFulfilmentPick::class, [[]], Cin7Payloads::load('sale/fulfilment/pick', 'get.response'), SaleFulfilmentPickData::class, ''],
    ],
    'bodies' => [
        SaleFulfilmentPickPostData::class => [SaleFulfilmentPickPostData::class, Cin7Payloads::load('sale/fulfilment/pick', 'post.request')],
        SaleFulfilmentPickPutData::class => [SaleFulfilmentPickPutData::class, Cin7Payloads::load('sale/fulfilment/pick', 'put.request')],
    ],
    'missing' => [
        'pick without Status' => [SaleFulfilmentPickData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/pick', 'get.response'), 'Status')],
        'pick POST without TaskID' => [SaleFulfilmentPickPostData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/pick', 'post.request'), 'TaskID')],
        'pick PUT without TaskID' => [SaleFulfilmentPickPutData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/pick', 'put.request'), 'TaskID')],
    ],
    'required' => [
        SaleFulfilmentPickData::class => ['TaskID', 'Status'],
        SaleFulfilmentPickPostData::class => ['TaskID'],
        SaleFulfilmentPickPutData::class => ['TaskID'],
    ],
];
