<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationCompletePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Complete\PutProductionOrderRunOperationComplete;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/operation/complete`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunOperationComplete::class => [
            PutProductionOrderRunOperationComplete::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithoutOutput' => true]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/complete',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithoutOutput' => true],
        ],
    ],
    'resources' => [
        'production order run operation complete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->complete(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithoutOutput' => true]),
            PutProductionOrderRunOperationComplete::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/complete',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithoutOutput' => true],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationComplete::class => [PutProductionOrderRunOperationComplete::class, [[]], Cin7Payloads::load('production/order/run/operation/complete', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationCompletePutData production/order/run/operation/complete' => [ProductionRunOperationCompletePutData::class, Cin7Payloads::load('production/order/run/operation/complete', 'put.request')],
    ],
];
