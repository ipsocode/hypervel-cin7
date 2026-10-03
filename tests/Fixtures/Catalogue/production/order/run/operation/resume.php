<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationResumePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Resume\PutProductionOrderRunOperationResume;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/operation/resume`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunOperationResume::class => [
            PutProductionOrderRunOperationResume::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/resume',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id],
        ],
    ],
    'resources' => [
        'production order run operation resume' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->resume(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id]),
            PutProductionOrderRunOperationResume::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/resume',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationResume::class => [PutProductionOrderRunOperationResume::class, [[]], Cin7Payloads::load('production/order/run/operation/resume', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationResumePutData production/order/run/operation/resume' => [ProductionRunOperationResumePutData::class, Cin7Payloads::load('production/order/run/operation/resume', 'put.request')],
    ],
];
