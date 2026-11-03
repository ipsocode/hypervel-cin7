<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunCompletePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Complete\PutProductionOrderRunComplete;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/complete`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunComplete::class => [
            PutProductionOrderRunComplete::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/complete',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id],
        ],
        PutProductionOrderRunComplete::class . ' with data' => [
            PutProductionOrderRunComplete::class,
            [fn (): ProductionRunCompletePutData => ProductionRunCompletePutData::from(Cin7Payloads::load('production/order/run/complete', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/complete',
            [],
            [
                'ProductionOrderID' => '1c634596-eb51-4ff1-828b-ee1b42a9a5f1',
                'ProductionRunID' => '1c634596-eb51-4ff1-828b-ee1b42a9a5f1',
                'EffectiveDate' => '2021-03-05T00:00:00',
                'FinishedProducts' => [
                    [
                        'Quantity' => 4920.0,
                        'WastageQuantity' => 0.0,
                        'ReceivedDate' => '2022-05-12T00:00:00',
                        'Received' => true,
                        'LocationID' => '70c7737c-4233-4655-a960-f7cd742bab4b',
                        'ProductID' => '27670203-9136-4ef8-96c3-33ffc41149da',
                        'BatchSN' => 'MO-01753/1-1',
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'production order run complete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->complete(['ProductionOrderID' => $id, 'ProductionRunID' => $id]),
            PutProductionOrderRunComplete::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunComplete::class => [PutProductionOrderRunComplete::class, [[]], Cin7Payloads::load('production/order/run/complete', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunCompletePutData production/order/run/complete' => [ProductionRunCompletePutData::class, PutProductionOrderRunComplete::class, Cin7Payloads::load('production/order/run/complete', 'put.request')],
    ],
];
