<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationResumePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Operation\Resume\PutProductionOrderRunOperationResume;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/run/operation/resume`; tests/Catalogue.php merges every file's rows by kind.

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
        PutProductionOrderRunOperationResume::class . ' with data' => [
            PutProductionOrderRunOperationResume::class,
            [fn (): ProductionRunOperationResumePutData => ProductionRunOperationResumePutData::from(Cin7Payloads::load('production/order/run/operation/resume', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/operation/resume',
            [],
            [
                'ProductionOrderID' => '0230bf59-59b3-4a3e-9fc8-aedcbb6b2815',
                'ProductionRunID' => 'a093ae70-c620-2318-c470-db45feb1ebf9',
                'RunOperationID' => 'e7659fbb-f5e4-0fd8-3b55-9a53bbf842ea',
            ],
        ],
    ],
    'resources' => [
        'production order run operation resume' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->operation()->resume(['ProductionOrderID' => $id, 'ProductionRunID' => $id, 'RunOperationID' => $id]),
            PutProductionOrderRunOperationResume::class,
        ],
    ],
    'dtos' => [
        PutProductionOrderRunOperationResume::class => [PutProductionOrderRunOperationResume::class, [[]], Cin7Payloads::load('production/order/run/operation/resume', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunOperationResumePutData production/order/run/operation/resume' => [ProductionRunOperationResumePutData::class, PutProductionOrderRunOperationResume::class, Cin7Payloads::load('production/order/run/operation/resume', 'put.request')],
    ],
];
