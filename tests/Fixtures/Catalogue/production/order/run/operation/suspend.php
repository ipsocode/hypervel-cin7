<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationSuspendPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Suspend\PutProductionOrderRunOperationSuspend;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/operation/suspend`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunOperationSuspend::class => [
            PutProductionOrderRunOperationSuspend::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'SuspendReasonID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/suspend',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'SuspendReasonID' => $id],
        ],
        PutProductionOrderRunOperationSuspend::class . ' with data' => [
            PutProductionOrderRunOperationSuspend::class,
            [fn (): ProductionRunOperationSuspendPutData => ProductionRunOperationSuspendPutData::from(Cin7Payloads::load('production/order/run/operation/suspend', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/suspend',
            [],
            [
                'ProductionOrderID' => '0230bf59-59b3-4a3e-9fc8-aedcbb6b2815',
                'ProductionRunID' => 'a093ae70-c620-2318-c470-db45feb1ebf9',
                'RunOperationID' => 'e7659fbb-f5e4-0fd8-3b55-9a53bbf842ea',
                'SuspendReasonID' => '68D56636-1B47-4780-BFFD-4202C4C2DA1B',
            ],
        ],
    ],
    'resources' => [
        'production order run operation suspend' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->suspend(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'SuspendReasonID' => $id]),
            PutProductionOrderRunOperationSuspend::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationSuspend::class => [PutProductionOrderRunOperationSuspend::class, [[]], Cin7Payloads::load('production/order/run/operation/suspend', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationSuspendPutData production/order/run/operation/suspend' => [ProductionRunOperationSuspendPutData::class, PutProductionOrderRunOperationSuspend::class, Cin7Payloads::load('production/order/run/operation/suspend', 'put.request')],
    ],
];
