<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationCompletePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Complete\PutProductionOrderRunOperationComplete;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/operation/complete`; tests/Catalogue.php merges every file's rows by kind.

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
        PutProductionOrderRunOperationComplete::class . ' with data' => [
            PutProductionOrderRunOperationComplete::class,
            [static function (): ProductionRunOperationCompletePutData {
                $fixture = Cin7Payloads::load('production/order/run/operation/complete', 'put.request');
                Arr::set($fixture, 'FinishedProducts.0.Received', true);
                Arr::set($fixture, 'FinishedProducts.1.Received', true);
                Arr::set($fixture, 'FinishedProducts.2.Received', true);

                return ProductionRunOperationCompletePutData::from($fixture);
            }],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/complete',
            [],
            [
                'ProductionOrderID' => '0230bf59-59b3-4a3e-9fc8-aedcbb6b2815',
                'ProductionRunID' => 'a093ae70-c620-2318-c470-db45feb1ebf9',
                'RunOperationID' => 'e7659fbb-f5e4-0fd8-3b55-9a53bbf842ea',
                'WithAutoConsume' => false,
                'WithoutOutput' => true,
                'AutoGenerateBatchSerialNumbers' => false,
                'ManualEndDate' => '2022-07-07',
                'FinishedProducts' => [
                    [
                        'Quantity' => 5.0,
                        'WastageQuantity' => 0.0,
                        'ReceivedDate' => '2021-03-01T05:26:25.08Z',
                        'Received' => true,
                        'LocationID' => '1bdcc906-fddd-4205-959b-720dea24af72',
                        'ProductID' => '48dc7ed4-6c31-4db5-b9f3-951b3d638cd0',
                        'BatchSN' => 'MO-00485/1-1',
                    ],
                    [
                        'Quantity' => 1.0,
                        'WastageQuantity' => 0.0,
                        'ReceivedDate' => '2021-03-01T05:26:25.08Z',
                        'Received' => true,
                        'LocationID' => '1bdcc906-fddd-4205-959b-720dea24af72',
                        'ProductID' => '188a5e9c-59cb-4de9-8656-2ac2d13ad7e0',
                        'BatchSN' => 'MO-00485/1-2',
                        'ExpiryDate' => '2021-03-03T00:00:00',
                    ],
                    [
                        'Quantity' => 1.0,
                        'WastageQuantity' => 0.0,
                        'ReceivedDate' => '2021-03-01T05:26:25.08Z',
                        'Received' => true,
                        'LocationID' => '1bdcc906-fddd-4205-959b-720dea24af72',
                        'ProductID' => '188a5e9c-59cb-4de9-8656-2ac2d13ad7e0',
                        'BatchSN' => 'MO-00485/1-4',
                        'ExpiryDate' => '2021-03-03T00:00:00',
                    ],
                    [
                        'Quantity' => 0.0,
                        'WastageQuantity' => 1.0,
                        'ReceivedDate' => '2021-03-01T05:26:25.08Z',
                        'Received' => true,
                        'LocationID' => '1bdcc906-fddd-4205-959b-720dea24af72',
                        'ProductID' => '188a5e9c-59cb-4de9-8656-2ac2d13ad7e0',
                        'BatchSN' => 'MO-00485/1-3',
                        'ExpiryDate' => '2021-03-03T00:00:00',
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'production order run operation complete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->complete(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'WithoutOutput' => true]),
            PutProductionOrderRunOperationComplete::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationComplete::class => [PutProductionOrderRunOperationComplete::class, [[]], Cin7Payloads::load('production/order/run/operation/complete', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationCompletePutData production/order/run/operation/complete' => [ProductionRunOperationCompletePutData::class, PutProductionOrderRunOperationComplete::class, Cin7Payloads::load('production/order/run/operation/complete', 'put.request')],
    ],
];
