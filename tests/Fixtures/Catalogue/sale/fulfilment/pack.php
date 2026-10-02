<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackPostData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\GetSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PostSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PutSaleFulfilmentPack;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/fulfilment/pack`; tests/Catalogue.php merges every file's rows by kind.

$line = ['SKU' => 'Bread', 'Quantity' => 1, 'Location' => 'Main Warehouse', 'Box' => 'Box 1'];
$lineSent = ['SKU' => 'Bread', 'Quantity' => 1.0, 'Location' => 'Main Warehouse', 'Box' => 'Box 1'];

return [
    'requests' => [
        GetSaleFulfilmentPack::class => [
            GetSaleFulfilmentPack::class,
            ['cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'includeProductInfo' => false],
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/pack',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'IncludeProductInfo' => 'false'],
            null,
        ],
        PostSaleFulfilmentPack::class => [
            PostSaleFulfilmentPack::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        PutSaleFulfilmentPack::class => [
            PutSaleFulfilmentPack::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED']],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED'],
        ],
        PostSaleFulfilmentPack::class . ' with data' => [
            PostSaleFulfilmentPack::class,
            [fn (): SaleFulfilmentPackPostData => SaleFulfilmentPackPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['Status' => 'DRAFT', 'Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
        PutSaleFulfilmentPack::class . ' with data' => [
            PutSaleFulfilmentPack::class,
            [fn (): SaleFulfilmentPackData => SaleFulfilmentPackData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED'])],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
    ],
    'resources' => [
        'sale fulfilment pack get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pack()->get('cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', includeProductInfo: true),
            GetSaleFulfilmentPack::class,
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/pack',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'IncludeProductInfo' => 'true'],
            null,
        ],
        'sale fulfilment pack post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pack()->post(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']),
            PostSaleFulfilmentPack::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        'sale fulfilment pack post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pack()->post(SaleFulfilmentPackPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED'])),
            PostSaleFulfilmentPack::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
        'sale fulfilment pack put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pack()->put(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']),
            PutSaleFulfilmentPack::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        'sale fulfilment pack put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->pack()->put(SaleFulfilmentPackData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT', 'Lines' => [$line]])),
            PutSaleFulfilmentPack::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/pack',
            [],
            ['Status' => 'DRAFT', 'Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
        ],
    ],
    'dtos' => [
        GetSaleFulfilmentPack::class => [GetSaleFulfilmentPack::class, ['task-1'], Cin7Payloads::load('sale/fulfilment/pack', 'get.response'), SaleFulfilmentPackData::class, ''],
        PostSaleFulfilmentPack::class => [PostSaleFulfilmentPack::class, [[]], Cin7Payloads::load('sale/fulfilment/pack', 'get.response'), SaleFulfilmentPackData::class, ''],
        PutSaleFulfilmentPack::class => [PutSaleFulfilmentPack::class, [[]], Cin7Payloads::load('sale/fulfilment/pack', 'get.response'), SaleFulfilmentPackData::class, ''],
    ],
    'bodies' => [
        SaleFulfilmentPackPostData::class => [SaleFulfilmentPackPostData::class, Cin7Payloads::load('sale/fulfilment/pack', 'post.request')],
        'pack PUT' => [SaleFulfilmentPackData::class, Cin7Payloads::load('sale/fulfilment/pack', 'put.request')],
    ],
    'missing' => [
        'pack without Status' => [SaleFulfilmentPackData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/pack', 'get.response'), 'Status')],
        'pack POST without TaskID' => [SaleFulfilmentPackPostData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/pack', 'post.request'), 'TaskID')],
    ],
    'required' => [
        SaleFulfilmentPackData::class => ['TaskID', 'Status'],
        SaleFulfilmentPackPostData::class => ['TaskID', 'Status'],
    ],
];
