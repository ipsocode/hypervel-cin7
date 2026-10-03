<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\OrderList\ProductionOrderListData;
use Ipsocode\Cin7\Enums\ProductionOrderListStatus;
use Ipsocode\Cin7\Requests\Production\OrderList\GetProductionOrderList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/orderList`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionOrderList::class => [
            GetProductionOrderList::class,
            ['status' => ProductionOrderListStatus::Draft, 'search' => 'x', 'locationId' => 'x', 'requiredByDateFrom' => '2024-01-01T00:00:00', 'requiredByDateTo' => '2024-01-01T00:00:00', 'completionDateFrom' => '2024-01-01T00:00:00', 'completionDateTo' => '2024-01-01T00:00:00', 'sourceTaskId' => 'x'],
            Method::GET,
            '/ExternalApi/v2/production/orderList',
            ['Status' => 'Draft', 'Search' => 'x', 'LocationID' => 'x', 'RequiredByDateFrom' => '2024-01-01T00:00:00', 'RequiredByDateTo' => '2024-01-01T00:00:00', 'CompletionDateFrom' => '2024-01-01T00:00:00', 'CompletionDateTo' => '2024-01-01T00:00:00', 'SourceTaskID' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'production orderList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->orderList()->get(status: ProductionOrderListStatus::Draft, search: 'x', locationId: 'x', requiredByDateFrom: '2024-01-01T00:00:00', requiredByDateTo: '2024-01-01T00:00:00', completionDateFrom: '2024-01-01T00:00:00', completionDateTo: '2024-01-01T00:00:00', sourceTaskId: 'x'),
            GetProductionOrderList::class,
            Method::GET,
            '/ExternalApi/v2/production/orderList',
            ['Status' => 'Draft', 'Search' => 'x', 'LocationID' => 'x', 'RequiredByDateFrom' => '2024-01-01T00:00:00', 'RequiredByDateTo' => '2024-01-01T00:00:00', 'CompletionDateFrom' => '2024-01-01T00:00:00', 'CompletionDateTo' => '2024-01-01T00:00:00', 'SourceTaskID' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
        'production orderList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->orderList()->paginate()->current(),
            GetProductionOrderList::class,
            Method::GET,
            '/ExternalApi/v2/production/orderList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetProductionOrderList::class => [GetProductionOrderList::class, [], Cin7Payloads::load('production/orderList', 'get.response'), ProductionOrderListData::class, 'ProductionOrderListItems'],
    ],
];
