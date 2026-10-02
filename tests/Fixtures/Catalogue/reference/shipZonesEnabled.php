<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\ShipZonesEnabled\ShipZonesEnabledData;
use Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled\GetShipZonesEnabled;
use Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled\PutShipZonesEnabled;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `reference/shipZonesEnabled`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetShipZonesEnabled::class => [
            GetShipZonesEnabled::class,
            [],
            Method::GET,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            null,
        ],
        PutShipZonesEnabled::class => [
            PutShipZonesEnabled::class,
            [['IsEnabled' => true]],
            Method::PUT,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            ['IsEnabled' => true],
        ],
        PutShipZonesEnabled::class . ' with data' => [
            PutShipZonesEnabled::class,
            [fn (): ShipZonesEnabledData => ShipZonesEnabledData::from(['IsEnabled' => false])],
            Method::PUT,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            ['IsEnabled' => false],
        ],
    ],
    'resources' => [
        'reference shipZonesEnabled get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZonesEnabled()->get(),
            GetShipZonesEnabled::class,
            Method::GET,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            null,
        ],
        'reference shipZonesEnabled put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZonesEnabled()->put(['IsEnabled' => true]),
            PutShipZonesEnabled::class,
            Method::PUT,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            ['IsEnabled' => true],
        ],
        'reference shipZonesEnabled put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZonesEnabled()->put(ShipZonesEnabledData::from(['IsEnabled' => false])),
            PutShipZonesEnabled::class,
            Method::PUT,
            '/ExternalApi/v2/reference/shipZonesEnabled',
            [],
            ['IsEnabled' => false],
        ],
    ],
    'dtos' => [
        GetShipZonesEnabled::class => [GetShipZonesEnabled::class, [], Cin7Payloads::load('reference/shipZonesEnabled', 'get.response'), ShipZonesEnabledData::class, ''],
        PutShipZonesEnabled::class => [PutShipZonesEnabled::class, [[]], Cin7Payloads::load('reference/shipZonesEnabled', 'put.response'), ShipZonesEnabledData::class, ''],
    ],
    'bodies' => [
        ShipZonesEnabledData::class => [ShipZonesEnabledData::class, Cin7Payloads::load('reference/shipZonesEnabled', 'put.request')],
    ],
    'missing' => [
        'ship zones enabled without IsEnabled' => [ShipZonesEnabledData::class, []],
    ],
    'required' => [
        ShipZonesEnabledData::class => ['IsEnabled'],
    ],
];
