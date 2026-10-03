<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderReferenceData;
use Ipsocode\Cin7\Requests\Production\Order\ReferenceData\GetProductionOrderReferenceData;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/referenceData`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionOrderReferenceData::class => [
            GetProductionOrderReferenceData::class,
            [],
            Method::GET,
            '/ExternalApi/v2/production/order/referenceData',
            [],
            null,
        ],
    ],
    'resources' => [
        'production order referenceData' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->referenceData(),
            GetProductionOrderReferenceData::class,
            Method::GET,
            '/ExternalApi/v2/production/order/referenceData',
            [],
            null,
        ],
    ],
    'dtos' => [
        GetProductionOrderReferenceData::class => [GetProductionOrderReferenceData::class, [], Cin7Payloads::load('production/order/referenceData', 'get.response'), ProductionOrderReferenceData::class, ''],
    ],
];
