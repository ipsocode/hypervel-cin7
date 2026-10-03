<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTakeList\StockTakeListData;
use Ipsocode\Cin7\Enums\StockTakeStatus;
use Ipsocode\Cin7\Requests\StockTakeList\GetStockTakeList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockTakeList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetStockTakeList::class => [
            GetStockTakeList::class,
            ['status' => StockTakeStatus::InProgress],
            Method::GET,
            '/ExternalApi/v2/stockTakeList',
            ['Status' => 'IN PROGRESS', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'stockTakeList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTakeList()->get(status: StockTakeStatus::Draft),
            GetStockTakeList::class,
            Method::GET,
            '/ExternalApi/v2/stockTakeList',
            ['Status' => 'DRAFT', 'page' => 1, 'limit' => 100],
            null,
        ],
        'stockTakeList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTakeList()->paginate()->current(),
            GetStockTakeList::class,
            Method::GET,
            '/ExternalApi/v2/stockTakeList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetStockTakeList::class => [
            GetStockTakeList::class,
            [],
            Cin7Payloads::load('stockTakeList', 'get.response'),
            StockTakeListData::class,
            'StockAdjustmentList',
        ],
    ],
];
