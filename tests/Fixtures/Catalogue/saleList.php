<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `saleList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSaleList::class => [
            GetSaleList::class,
            ['status' => SaleStatus::Ordered, 'readyForShipping' => true],
            Method::GET,
            '/ExternalApi/v2/saleList',
            ['Status' => 'ORDERED', 'ReadyForShipping' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'saleList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->saleList()->get(status: SaleStatus::Ordered),
            GetSaleList::class,
            Method::GET,
            '/ExternalApi/v2/saleList',
            ['Status' => 'ORDERED', 'page' => 1, 'limit' => 100],
            null,
        ],
        'saleList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->saleList()->paginate()->current(),
            GetSaleList::class,
            Method::GET,
            '/ExternalApi/v2/saleList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetSaleList::class => [
            GetSaleList::class,
            [],
            Cin7Payloads::saleList(),
            SaleListData::class,
            'SaleList',
        ],
    ],
    'required' => [
        SaleListData::class => ['SaleID', 'OrderNumber', 'Status', 'OrderDate', 'Customer', 'InvoiceAmount', 'PaidAmount', 'BaseCurrency', 'CustomerCurrency', 'Updated', 'OrderStatus', 'CombinedPickingStatus', 'CombinedPackingStatus', 'CombinedShippingStatus', 'FulFilmentStatus', 'CombinedInvoiceStatus', 'CreditNoteStatus', 'CombinedPaymentStatus', 'Type', 'QuoteStatus', 'CombinedTrackingNumbers'],
    ],
];
