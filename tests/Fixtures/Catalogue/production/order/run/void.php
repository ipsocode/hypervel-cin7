<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Void\PutProductionOrderRunVoid;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/void`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunVoid::class => [
            PutProductionOrderRunVoid::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'ApplyToRelatedTasks' => true]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/void',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'ApplyToRelatedTasks' => true],
        ],
    ],
    'resources' => [
        'production order run void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->void(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'ApplyToRelatedTasks' => true]),
            PutProductionOrderRunVoid::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/void',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'ApplyToRelatedTasks' => true],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunVoid::class => [PutProductionOrderRunVoid::class, [[]], Cin7Payloads::load('production/order/run/void', 'put.response'), ProductionRunUndoData::class, ''],
    ],
    'bodies' => [
        'ProductionRunUndoData production/order/run/void' => [ProductionRunUndoData::class, Cin7Payloads::load('production/order/run/void', 'put.request')],
    ],
];
