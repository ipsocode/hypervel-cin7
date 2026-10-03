<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\SuspendReason\SuspendReasonData;
use Ipsocode\Cin7\Requests\Production\SuspendReason\GetProductionSuspendReason;
use Ipsocode\Cin7\Requests\Production\SuspendReason\PutProductionSuspendReason;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/suspendReason`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionSuspendReason::class => [
            GetProductionSuspendReason::class,
            ['workcenterId' => 'x'],
            Method::GET,
            '/ExternalApi/v2/production/suspendReason',
            ['WorkcenterID' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
        PutProductionSuspendReason::class => [
            PutProductionSuspendReason::class,
            [['Reason' => 'Break']],
            Method::PUT,
            '/ExternalApi/v2/production/suspendReason',
            [],
            ['Reason' => 'Break'],
        ],
    ],
    'resources' => [
        'production suspendReason get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->suspendReason()->get(workcenterId: 'x'),
            GetProductionSuspendReason::class,
            Method::GET,
            '/ExternalApi/v2/production/suspendReason',
            ['WorkcenterID' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
        'production suspendReason paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->suspendReason()->paginate()->current(),
            GetProductionSuspendReason::class,
            Method::GET,
            '/ExternalApi/v2/production/suspendReason',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'production suspendReason put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->suspendReason()->put(['Reason' => 'Break']),
            PutProductionSuspendReason::class,
            Method::PUT,
            '/ExternalApi/v2/production/suspendReason',
            [],
            ['Reason' => 'Break'],
        ],
    ],
    'dtos' => [
        GetProductionSuspendReason::class => [GetProductionSuspendReason::class, [], Cin7Payloads::load('production/suspendReason', 'get.response'), SuspendReasonData::class, 'SuspendReasons'],
        PutProductionSuspendReason::class => [PutProductionSuspendReason::class, [[]], Cin7Payloads::load('production/suspendReason', 'put.response'), SuspendReasonData::class, ''],
    ],
    'bodies' => [
        'SuspendReasonData production/suspendReason' => [SuspendReasonData::class, Cin7Payloads::load('production/suspendReason', 'put.request')],
    ],
];
