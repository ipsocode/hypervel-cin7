<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\StockAdjustmentList\StockAdjustmentListData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\StockAdjustmentList\GetStockAdjustmentList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `stockadjustmentList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetStockAdjustmentList::class => [
            GetStockAdjustmentList::class,
            ['status' => CompletionStatus::Completed],
            Method::GET,
            '/ExternalApi/v2/stockadjustmentList',
            ['Status' => 'COMPLETED', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'stockAdjustmentList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustmentList()->get(status: CompletionStatus::Draft),
            GetStockAdjustmentList::class,
            Method::GET,
            '/ExternalApi/v2/stockadjustmentList',
            ['Status' => 'DRAFT', 'page' => 1, 'limit' => 100],
            null,
        ],
        'stockAdjustmentList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->stockAdjustmentList()->paginate()->current(),
            GetStockAdjustmentList::class,
            Method::GET,
            '/ExternalApi/v2/stockadjustmentList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetStockAdjustmentList::class => [
            GetStockAdjustmentList::class,
            [],
            Cin7Payloads::load('stockadjustmentList', 'get.response'),
            StockAdjustmentListData::class,
            'StockAdjustmentList',
        ],
    ],
];
