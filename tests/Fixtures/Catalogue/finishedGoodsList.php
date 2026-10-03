<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\FinishedGoodsList\FinishedGoodsListData;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;
use Ipsocode\Cin7\Requests\FinishedGoodsList\GetFinishedGoodsList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `finishedGoodsList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetFinishedGoodsList::class => [
            GetFinishedGoodsList::class,
            ['status' => FinishedGoodsStatus::InProgress, 'search' => 'Bread', 'saleId' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            Method::GET,
            '/ExternalApi/v2/finishedGoodsList',
            ['Status' => 'IN PROGRESS', 'Search' => 'Bread', 'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'finishedGoodsList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoodsList()->get(status: FinishedGoodsStatus::Draft, search: 'Bin'),
            GetFinishedGoodsList::class,
            Method::GET,
            '/ExternalApi/v2/finishedGoodsList',
            ['Status' => 'DRAFT', 'Search' => 'Bin', 'page' => 1, 'limit' => 100],
            null,
        ],
        'finishedGoodsList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->finishedGoodsList()->paginate()->current(),
            GetFinishedGoodsList::class,
            Method::GET,
            '/ExternalApi/v2/finishedGoodsList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetFinishedGoodsList::class => [GetFinishedGoodsList::class, [], Cin7Payloads::load('finishedGoodsList', 'get.response'), FinishedGoodsListData::class, 'FinishedGoods'],
    ],
];
