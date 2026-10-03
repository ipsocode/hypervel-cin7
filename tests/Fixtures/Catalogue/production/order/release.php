<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderReleasePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\Production\Order\Release\PostProductionOrderRelease;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/release`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PostProductionOrderRelease::class => [
            PostProductionOrderRelease::class,
            [['productionOrderID' => $id, 'releaseDate' => '2024-05-01T00:00:00']],
            Method::POST,
            '/ExternalApi/v2/production/order/release',
            [],
            ['productionOrderID' => $id, 'releaseDate' => '2024-05-01T00:00:00'],
        ],
    ],
    'resources' => [
        'production order release' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->release(['productionOrderID' => $id, 'releaseDate' => '2024-05-01T00:00:00']),
            PostProductionOrderRelease::class,
            Method::POST,
            '/ExternalApi/v2/production/order/release',
            [],
            ['productionOrderID' => $id, 'releaseDate' => '2024-05-01T00:00:00'],
        ],
    ],
    'dtos' => [
        PostProductionOrderRelease::class => [PostProductionOrderRelease::class, [[]], Cin7Payloads::load('production/order/release', 'post.response'), ProductionOrdersData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderReleasePostData production/order/release' => [ProductionOrderReleasePostData::class, Cin7Payloads::load('production/order/release', 'post.request')],
    ],
];
