<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderMessageData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderUndoPostData;
use Ipsocode\Cin7\Requests\Production\Order\Undo\PostProductionOrderUndo;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/undo`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PostProductionOrderUndo::class => [
            PostProductionOrderUndo::class,
            [['productionOrderID' => $id, 'ApplyToTasks' => true]],
            Method::POST,
            '/ExternalApi/v2/production/order/undo',
            [],
            ['productionOrderID' => $id, 'ApplyToTasks' => true],
        ],
    ],
    'resources' => [
        'production order undo' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->undo(['productionOrderID' => $id, 'ApplyToTasks' => true]),
            PostProductionOrderUndo::class,
            Method::POST,
            '/ExternalApi/v2/production/order/undo',
            [],
            ['productionOrderID' => $id, 'ApplyToTasks' => true],
        ],
    ],
    'dtos' => [
        PostProductionOrderUndo::class => [PostProductionOrderUndo::class, [[]], Cin7Payloads::load('production/order/undo', 'post.response'), ProductionOrderMessageData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderUndoPostData production/order/undo' => [ProductionOrderUndoPostData::class, Cin7Payloads::load('production/order/undo', 'post.request')],
    ],
];
