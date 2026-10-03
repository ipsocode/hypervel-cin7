<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunManualJournalsPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Production\Order\Run\ManualJournal\PutProductionOrderRunManualJournal;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run/manualJournal`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PutProductionOrderRunManualJournal::class => [
            PutProductionOrderRunManualJournal::class,
            [['RunID' => $id], 'productionOrderId' => $id],
            Method::PUT,
            '/ExternalApi/v2/production/order/run/manualJournal',
            ['ProductionOrderID' => $id],
            ['RunID' => $id],
        ],
    ],
    'resources' => [
        'production order run manualJournal' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->manualJournal(['RunID' => $id], $id),
            PutProductionOrderRunManualJournal::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run/manualJournal',
            ['ProductionOrderID' => $id],
            ['RunID' => $id],
        ],
    ],
    'dtos' => [
        PutProductionOrderRunManualJournal::class => [PutProductionOrderRunManualJournal::class, [[], $id], Cin7Payloads::load('production/order/run/manualJournal', 'put.response'), ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunManualJournalsPutData production/order/run/manualJournal' => [ProductionRunManualJournalsPutData::class, Cin7Payloads::load('production/order/run/manualJournal', 'put.request')],
    ],
];
