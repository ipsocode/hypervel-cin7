<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationStartPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Start\PutProductionOrderRunOperationStart;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/operation/start`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunOperationStart::class => [
            PutProductionOrderRunOperationStart::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithAutoConsume' => true]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/start',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithAutoConsume' => true],
        ],
    ],
    'resources' => [
        'production order run operation start' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->start(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithAutoConsume' => true]),
            PutProductionOrderRunOperationStart::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/start',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithAutoConsume' => true],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationStart::class => [PutProductionOrderRunOperationStart::class, [[]], Cin7Payloads::load('production/order/run/operation/start', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationStartPutData production/order/run/operation/start' => [ProductionRunOperationStartPutData::class, Cin7Payloads::load('production/order/run/operation/start', 'put.request')],
    ],
];
