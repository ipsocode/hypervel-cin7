<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipLinePostPutData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPutData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\GetSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PostSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PutSaleFulfilmentShip;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/fulfilment/ship`; tests/Catalogue.php merges every file's rows by kind.

$line = ['ShipmentDate' => '2017-11-22T00:00:00', 'Box' => 'Box 1', 'TrackingURL' => 'https://track.example/1'];
$lineSent = ['ShipmentDate' => '2017-11-22T00:00:00', 'Box' => 'Box 1'];

return [
    'requests' => [
        GetSaleFulfilmentShip::class => [
            GetSaleFulfilmentShip::class,
            ['cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/ship',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
            null,
        ],
        PostSaleFulfilmentShip::class => [
            PostSaleFulfilmentShip::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT']],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        PutSaleFulfilmentShip::class => [
            PutSaleFulfilmentShip::class,
            [['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED', 'AddTrackingNumbers' => true]],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED', 'AddTrackingNumbers' => true],
        ],
        PostSaleFulfilmentShip::class . ' with data' => [
            PostSaleFulfilmentShip::class,
            [fn (): SaleFulfilmentShipPostData => SaleFulfilmentShipPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED'],
        ],
        PutSaleFulfilmentShip::class . ' with data' => [
            PutSaleFulfilmentShip::class,
            [fn (): SaleFulfilmentShipPutData => SaleFulfilmentShipPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED', 'AddTrackingNumbers' => true])],
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['AddTrackingNumbers' => true, 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'AUTHORISED'],
        ],
    ],
    'resources' => [
        'sale fulfilment ship get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->ship()->get('cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'),
            GetSaleFulfilmentShip::class,
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment/ship',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
            null,
        ],
        'sale fulfilment ship post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->ship()->post(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT', 'Lines' => [$line]]),
            PostSaleFulfilmentShip::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT', 'Lines' => [$lineSent]],
        ],
        'sale fulfilment ship post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->ship()->post(SaleFulfilmentShipPostData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'])),
            PostSaleFulfilmentShip::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'DRAFT'],
        ],
        'sale fulfilment ship put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->ship()->put(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'PARTIALLY AUTHORISED']),
            PutSaleFulfilmentShip::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'PARTIALLY AUTHORISED'],
        ],
        'sale fulfilment ship put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->ship()->put(SaleFulfilmentShipPutData::from(['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'PARTIALLY AUTHORISED', 'Lines' => [$line]])),
            PutSaleFulfilmentShip::class,
            Method::PUT,
            '/ExternalApi/v2/sale/fulfilment/ship',
            [],
            ['Lines' => [$lineSent], 'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Status' => 'PARTIALLY AUTHORISED'],
        ],
    ],
    'dtos' => [
        GetSaleFulfilmentShip::class => [GetSaleFulfilmentShip::class, ['task-1'], Cin7Payloads::load('sale/fulfilment/ship', 'get.response'), SaleFulfilmentShipData::class, ''],
        PostSaleFulfilmentShip::class => [PostSaleFulfilmentShip::class, [[]], Cin7Payloads::load('sale/fulfilment/ship', 'get.response'), SaleFulfilmentShipData::class, ''],
        PutSaleFulfilmentShip::class => [PutSaleFulfilmentShip::class, [[]], Cin7Payloads::load('sale/fulfilment/ship', 'get.response'), SaleFulfilmentShipData::class, ''],
    ],
    'bodies' => [
        SaleFulfilmentShipPostData::class => [SaleFulfilmentShipPostData::class, Cin7Payloads::load('sale/fulfilment/ship', 'post.request')],
        SaleFulfilmentShipPutData::class => [SaleFulfilmentShipPutData::class, Cin7Payloads::load('sale/fulfilment/ship', 'put.request')],
    ],
    'missing' => [
        'ship POST without Status' => [SaleFulfilmentShipPostData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/ship', 'post.request'), 'Status')],
        'ship PUT without TaskID' => [SaleFulfilmentShipPutData::class, Arr::except(Cin7Payloads::load('sale/fulfilment/ship', 'put.request'), 'TaskID')],
        'ship line without Box' => [SaleFulfilmentShipLinePostPutData::class, ['ShipmentDate' => '2017-11-22T00:00:00', 'Boxes' => 'Box 1']],
    ],
    'required' => [
        SaleFulfilmentShipPostData::class => ['TaskID', 'Status'],
        SaleFulfilmentShipPutData::class => ['TaskID', 'Status'],
        SaleFulfilmentShipLinePostPutData::class => ['ShipmentDate', 'Box'],
    ],
    'omitted' => [
        PostSaleFulfilmentShip::class => [PostSaleFulfilmentShip::class, ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Lines' => [$line]], ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Lines' => [$lineSent]]],
        PutSaleFulfilmentShip::class => [PutSaleFulfilmentShip::class, ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Lines' => [$line]], ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Lines' => [$lineSent]]],
    ],
];
