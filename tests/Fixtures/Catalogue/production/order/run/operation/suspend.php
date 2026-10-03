<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationSuspendPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Suspend\PutProductionOrderRunOperationSuspend;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/operation/suspend`; tests/Catalogue.php merges every file's rows by kind.

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
    ],
    'resources' => [
        'production order run operation suspend' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->suspend(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'SuspendReasonID' => $id]),
            PutProductionOrderRunOperationSuspend::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/suspend',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id, 'SuspendReasonID' => $id],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationSuspend::class => [PutProductionOrderRunOperationSuspend::class, [[]], Cin7Payloads::load('production/order/run/operation/suspend', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationSuspendPutData production/order/run/operation/suspend' => [ProductionRunOperationSuspendPutData::class, Cin7Payloads::load('production/order/run/operation/suspend', 'put.request')],
    ],
];
