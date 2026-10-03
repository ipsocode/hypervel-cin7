<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunCompletePostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Complete\PutProductionOrderRunComplete;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/complete`; tests/Catalogue.php merges every file's rows by kind.

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
    ],
    'resources' => [
        'production order run complete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->complete(['ProductionOrderID' => $id, 'ProductionRunID' => $id]),
            PutProductionOrderRunComplete::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/complete',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunComplete::class => [PutProductionOrderRunComplete::class, [[]], Cin7Payloads::load('production/order/run/complete', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunCompletePostData production/order/run/complete' => [ProductionRunCompletePostData::class, Cin7Payloads::load('production/order/run/complete', 'put.request')],
    ],
];
