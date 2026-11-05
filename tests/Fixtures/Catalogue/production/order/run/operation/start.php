<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationStartPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Start\PutProductionOrderRunOperationStart;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/operation/start`; tests/Catalogue.php merges every file's rows by kind.

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
        PutProductionOrderRunOperationStart::class . ' with data' => [
            PutProductionOrderRunOperationStart::class,
            [fn (): ProductionRunOperationStartPutData => ProductionRunOperationStartPutData::from(Cin7Payloads::load('production/order/run/operation/start', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/start',
            [],
            [
                'ProductionOrderID' => '0230bf59-59b3-4a3e-9fc8-aedcbb6b2815',
                'ProductionRunID' => 'a093ae70-c620-2318-c470-db45feb1ebf9',
                'RunOperationID' => 'e7659fbb-f5e4-0fd8-3b55-9a53bbf842ea',
                'WithAutoConsume' => false,
                'ManualStartDate' => '2022-07-07',
                'InputProducts' => [
                    [
                        'Quantity' => 2.0,
                        'ProductCode' => 'T-2',
                        'BatchSN' => '1234',
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'production order run operation start' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->start(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithAutoConsume' => true]),
            PutProductionOrderRunOperationStart::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationStart::class => [PutProductionOrderRunOperationStart::class, [[]], Cin7Payloads::load('production/order/run/operation/start', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationStartPutData production/order/run/operation/start' => [ProductionRunOperationStartPutData::class, PutProductionOrderRunOperationStart::class, Cin7Payloads::load('production/order/run/operation/start', 'put.request')],
    ],
];
