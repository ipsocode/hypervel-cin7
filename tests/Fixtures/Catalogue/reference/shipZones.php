<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZoneData;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZonePostData;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZonePutData;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShipZoneAppliesToData;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShipZoneConditionData;
use Ipsocode\Cin7\Requests\Reference\ShipZones\DeleteShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\GetShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\PostShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\PutShipZones;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `reference/shipZones`; tests/Catalogue.php merges every file's rows by kind.

// The fields every ship zone POST requires, and a zone to change.
$fields = ['Name' => 'Zone', 'IsRestZone' => false, 'PricesInclTax' => false, 'Negative' => false];
$zoneId = 'b25830a0-3db8-4ea2-a6c3-25efe6381649';
$uri = '/ExternalApi/v2/reference/shipZones';

return [
    'requests' => [
        GetShipZones::class => [
            GetShipZones::class,
            ['id' => $zoneId, 'search' => 'Zone'],
            Method::GET,
            $uri,
            ['ID' => $zoneId, 'Search' => 'Zone', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostShipZones::class => [
            PostShipZones::class,
            [['Name' => 'Zone']],
            Method::POST,
            $uri,
            [],
            ['Name' => 'Zone'],
        ],
        PutShipZones::class => [
            PutShipZones::class,
            [['ZoneID' => $zoneId, 'Name' => 'Zone v2']],
            Method::PUT,
            $uri,
            [],
            ['ZoneID' => $zoneId, 'Name' => 'Zone v2'],
        ],
        DeleteShipZones::class => [
            DeleteShipZones::class,
            [$zoneId],
            Method::DELETE,
            $uri,
            ['ShipZoneID' => $zoneId],
            null,
        ],
        PostShipZones::class . ' with data' => [
            PostShipZones::class,
            [fn (): ShippingZonePostData => ShippingZonePostData::from([...$fields, 'AppliesTo' => [['Country2' => 'DZ', 'ShippingRate' => 7000]]])],
            Method::POST,
            $uri,
            [],
            ['IsRestZone' => false, 'PricesInclTax' => false, 'Negative' => false, 'AppliesTo' => [['ShippingRate' => 7000.0, 'Country2' => 'DZ']], 'Name' => 'Zone'],
        ],
        PutShipZones::class . ' with data' => [
            PutShipZones::class,
            [fn (): ShippingZonePutData => ShippingZonePutData::from(['ZoneID' => $zoneId, 'Name' => 'Zone v2', 'Conditions' => [['ShippingCost' => 7, 'ConditionType' => 'Price']]])],
            Method::PUT,
            $uri,
            [],
            ['ZoneID' => $zoneId, 'Conditions' => [['ShippingCost' => 7.0, 'ConditionType' => 'Price']], 'Name' => 'Zone v2'],
        ],
    ],
    'resources' => [
        'reference shipZones get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->get(search: 'Zone'),
            GetShipZones::class,
            Method::GET,
            $uri,
            ['Search' => 'Zone', 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference shipZones paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->paginate(id: $zoneId)->current(),
            GetShipZones::class,
            Method::GET,
            $uri,
            ['ID' => $zoneId, 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference shipZones post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->post(['Name' => 'Zone']),
            PostShipZones::class,
            Method::POST,
            $uri,
            [],
            ['Name' => 'Zone'],
        ],
        'reference shipZones post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->post(ShippingZonePostData::from($fields)),
            PostShipZones::class,
            Method::POST,
            $uri,
            [],
            ['IsRestZone' => false, 'PricesInclTax' => false, 'Negative' => false, 'Name' => 'Zone'],
        ],
        'reference shipZones put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->put(['ZoneID' => $zoneId, 'Name' => 'Zone v2']),
            PutShipZones::class,
            Method::PUT,
            $uri,
            [],
            ['ZoneID' => $zoneId, 'Name' => 'Zone v2'],
        ],
        'reference shipZones put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->put(ShippingZonePutData::from(['ZoneID' => $zoneId, 'Name' => 'Zone v2'])),
            PutShipZones::class,
            Method::PUT,
            $uri,
            [],
            ['ZoneID' => $zoneId, 'Name' => 'Zone v2'],
        ],
        'reference shipZones delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->shipZones()->delete($zoneId),
            DeleteShipZones::class,
            Method::DELETE,
            $uri,
            ['ShipZoneID' => $zoneId],
            null,
        ],
    ],
    'dtos' => [
        GetShipZones::class => [GetShipZones::class, [], Cin7Payloads::load('reference/shipZones', 'get.response'), ShippingZoneData::class, 'ShipZones'],
        PostShipZones::class => [PostShipZones::class, [[]], Cin7Payloads::load('reference/shipZones', 'post.response'), ShippingZoneData::class, 'ShipZones.0'],
        PutShipZones::class => [PutShipZones::class, [[]], Cin7Payloads::load('reference/shipZones', 'put.response'), ShippingZoneData::class, 'ShipZones.0'],
    ],
    'bodies' => [
        ShippingZonePostData::class => [ShippingZonePostData::class, Cin7Payloads::load('reference/shipZones', 'post.request')],
        ShippingZonePutData::class => [ShippingZonePutData::class, Cin7Payloads::load('reference/shipZones', 'put.request')],
    ],
    'missing' => [
        'ship zone POST without Name' => [ShippingZonePostData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'post.request'), 'Name')],
        'ship zone POST without Negative' => [ShippingZonePostData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'post.request'), 'Negative')],
        'ship zone PUT without ZoneID' => [ShippingZonePutData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'put.request'), 'ZoneID')],
        'ship zone without DefaultShippingCost' => [ShippingZoneData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'get.response')['ShipZones'][0], 'DefaultShippingCost')],
        'ship zone applies-to without ShippingRate' => [ShipZoneAppliesToData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'post.request')['AppliesTo'][0], 'ShippingRate')],
        'ship zone condition without ShippingCost' => [ShipZoneConditionData::class, Arr::except(Cin7Payloads::load('reference/shipZones', 'post.request')['Conditions'][0], 'ShippingCost')],
    ],
    'required' => [
        ShippingZoneData::class => ['ZoneID', 'Name', 'IsRestZone', 'PricesInclTax', 'Negative', 'DefaultShippingCost'],
        ShippingZonePostData::class => ['Name', 'IsRestZone', 'PricesInclTax', 'Negative'],
        ShippingZonePutData::class => ['Name', 'ZoneID'],
        ShipZoneAppliesToData::class => ['ShippingRate'],
        ShipZoneConditionData::class => ['ShippingCost', 'ConditionType'],
    ],
];
