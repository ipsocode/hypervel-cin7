<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\InventoryWriteOffList\InventoryWriteOffListData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\InventoryWriteOffList\GetInventoryWriteOffList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `inventoryWriteOffList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetInventoryWriteOffList::class => [
            GetInventoryWriteOffList::class,
            ['status' => CompletionStatus::Completed, 'search' => 'FG-0003'],
            Method::GET,
            '/ExternalApi/v2/inventoryWriteOffList',
            ['Status' => 'COMPLETED', 'Search' => 'FG-0003', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'inventoryWriteOffList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOffList()->get(status: CompletionStatus::Draft, search: 'Main'),
            GetInventoryWriteOffList::class,
            Method::GET,
            '/ExternalApi/v2/inventoryWriteOffList',
            ['Status' => 'DRAFT', 'Search' => 'Main', 'page' => 1, 'limit' => 100],
            null,
        ],
        'inventoryWriteOffList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->inventoryWriteOffList()->paginate()->current(),
            GetInventoryWriteOffList::class,
            Method::GET,
            '/ExternalApi/v2/inventoryWriteOffList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetInventoryWriteOffList::class => [
            GetInventoryWriteOffList::class,
            [],
            Cin7Payloads::load('inventoryWriteOffList', 'get.response'),
            InventoryWriteOffListData::class,
            'InventoryWriteOffs',
        ],
    ],
];
