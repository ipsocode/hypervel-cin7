<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\Production\Order\Run\Undo\PutProductionOrderRunUndo;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/undo`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunUndo::class => [
            PutProductionOrderRunUndo::class,
            [['ProductionOrderID' => $id, 'ProductionRunID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/undo',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id],
        ],
    ],
    'resources' => [
        'production order run undo' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->undo(['ProductionOrderID' => $id, 'ProductionRunID' => $id]),
            PutProductionOrderRunUndo::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/undo',
            [],
            ['ProductionOrderID' => $id, 'ProductionRunID' => $id],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunUndo::class => [PutProductionOrderRunUndo::class, [[]], Cin7Payloads::load('production/order/run/undo', 'put.response'), ProductionRunUndoData::class, ''],
    ],
    'bodies' => [
        'ProductionRunUndoData production/order/run/undo' => [ProductionRunUndoData::class, Cin7Payloads::load('production/order/run/undo', 'put.request')],
    ],
];
