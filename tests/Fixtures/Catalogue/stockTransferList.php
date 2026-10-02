<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockTransferList\StockTransferListData;
use Ipsocode\Cin7\Enums\StockTransferStatus;
use Ipsocode\Cin7\Requests\StockTransferList\GetStockTransferList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockTransferList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetStockTransferList::class => [
            GetStockTransferList::class,
            ['status' => StockTransferStatus::InTransit, 'search' => 'Main'],
            Method::GET,
            '/ExternalApi/v2/stockTransferList',
            ['Status' => 'IN TRANSIT', 'Search' => 'Main', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'stockTransferList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransferList()->get(status: StockTransferStatus::Draft, search: 'Bin'),
            GetStockTransferList::class,
            Method::GET,
            '/ExternalApi/v2/stockTransferList',
            ['Status' => 'DRAFT', 'Search' => 'Bin', 'page' => 1, 'limit' => 100],
            null,
        ],
        'stockTransferList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockTransferList()->paginate()->current(),
            GetStockTransferList::class,
            Method::GET,
            '/ExternalApi/v2/stockTransferList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetStockTransferList::class => [
            GetStockTransferList::class,
            [],
            Cin7Payloads::load('stockTransferList', 'get.response'),
            StockTransferListData::class,
            'StockTransferList',
        ],
    ],
];
