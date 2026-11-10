<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Void\PutProductionOrderRunVoid;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/void`; tests/Catalogue.php merges every file's rows by kind.

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
        PutProductionOrderRunVoid::class . ' with data' => [
            PutProductionOrderRunVoid::class,
            [fn (): ProductionRunUndoData => ProductionRunUndoData::from(Cin7Payloads::load('production/order/run/void', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/void',
            [],
            [
                'ProductionOrderID' => '1c634596-eb51-4ff1-828b-ee1b42a9a5f1',
                'ProductionRunID' => '1c634596-eb51-4ff1-828b-ee1b42a9a5f1',
                'ApplyToRelatedTasks' => true,
            ],
        ],
    ],
    'resources' => [
        'production order run void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->void(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'ApplyToRelatedTasks' => true]),
            PutProductionOrderRunVoid::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunVoid::class => [PutProductionOrderRunVoid::class, [[]], Cin7Payloads::load('production/order/run/void', 'put.response'), ProductionRunUndoData::class, ''],
    ],
    'bodies' => [
        'ProductionRunUndoData production/order/run/void' => [ProductionRunUndoData::class, PutProductionOrderRunVoid::class, Cin7Payloads::load('production/order/run/void', 'put.request')],
    ],
];
