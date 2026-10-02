<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\SaleOrderData;
use Ipsocode\Cin7\Requests\Sale\Order\GetSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Order\PostSaleOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/order`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSaleOrder::class => [
            GetSaleOrder::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludeProductInfo' => true]],
            Method::GET,
            '/ExternalApi/v2/sale/order',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
            null,
        ],
        PostSaleOrder::class => [
            PostSaleOrder::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK']],
            Method::POST,
            '/ExternalApi/v2/sale/order',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'],
        ],
        PostSaleOrder::class . ' with data' => [
            PostSaleOrder::class,
            [fn (): SaleOrderData => SaleOrderData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Memo' => 'Rush'])],
            Method::POST,
            '/ExternalApi/v2/sale/order',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Memo' => 'Rush'],
        ],
    ],
    'resources' => [
        'sale order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->get('916ab4c0-6ccb-4c93-873d-0603859050e4', ['IncludeProductInfo' => true]),
            GetSaleOrder::class,
            Method::GET,
            '/ExternalApi/v2/sale/order',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
            null,
        ],
        'sale order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
            PostSaleOrder::class,
            Method::POST,
            '/ExternalApi/v2/sale/order',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale order post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->order()->post(SaleOrderData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'])),
            PostSaleOrder::class,
            Method::POST,
            '/ExternalApi/v2/sale/order',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'AutoPickPackShipMode' => 'NOPICK'],
        ],
    ],
    'dtos' => [
        GetSaleOrder::class => [GetSaleOrder::class, ['sale-1'], Cin7Payloads::saleOrder(), SaleOrderData::class, ''],
        PostSaleOrder::class => [PostSaleOrder::class, [[]], Cin7Payloads::saleOrder(), SaleOrderData::class, ''],
    ],
    'omitted' => [
        PostSaleOrder::class => [
            PostSaleOrder::class,
            ['SaleID' => 's', 'Lines' => [['SKU' => 'A', 'BackorderQuantity' => 2]]],
            ['SaleID' => 's', 'Lines' => [['SKU' => 'A']]],
        ],
    ],
];
