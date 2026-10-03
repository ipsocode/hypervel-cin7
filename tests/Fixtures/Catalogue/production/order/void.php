<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderMessageData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderVoidPostData;
use Ipsocode\Cin7\Requests\Production\Order\Void\PostProductionOrderVoid;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/void`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PostProductionOrderVoid::class => [
            PostProductionOrderVoid::class,
            [['productionOrderID' => $id, 'ApplyToTasks' => false]],
            Method::POST,
            '/ExternalApi/v2/production/order/void',
            [],
            ['productionOrderID' => $id, 'ApplyToTasks' => false],
        ],
    ],
    'resources' => [
        'production order void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->void(['productionOrderID' => $id, 'ApplyToTasks' => false]),
            PostProductionOrderVoid::class,
            Method::POST,
            '/ExternalApi/v2/production/order/void',
            [],
            ['productionOrderID' => $id, 'ApplyToTasks' => false],
        ],
    ],
    'dtos' => [
        PostProductionOrderVoid::class => [PostProductionOrderVoid::class, [[]], Cin7Payloads::load('production/order/void', 'post.response'), ProductionOrderMessageData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderVoidPostData production/order/void' => [ProductionOrderVoidPostData::class, Cin7Payloads::load('production/order/void', 'post.request')],
    ],
];
