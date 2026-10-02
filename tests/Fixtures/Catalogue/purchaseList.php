<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\PurchaseList\PurchaseListData;
use Ipsocode\Cin7\Enums\InvoiceStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\PurchaseList\GetPurchaseList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchaseList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetPurchaseList::class => [
            GetPurchaseList::class,
            ['updatedSince' => '2018-04-01T00:00:00', 'orderStatus' => TaskStatus::Authorised, 'invoiceStatus' => InvoiceStatus::Paid, 'status' => 'COMPLETED', 'dropShipTaskId' => '31760d16-c2e5-4620-9e09-0b236b776cef'],
            Method::GET,
            '/ExternalApi/v2/purchaseList',
            ['UpdatedSince' => '2018-04-01T00:00:00', 'OrderStatus' => 'AUTHORISED', 'InvoiceStatus' => 'PAID', 'Status' => 'COMPLETED', 'DropShipTaskID' => '31760d16-c2e5-4620-9e09-0b236b776cef', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'purchaseList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchaseList()->get(restockReceivedStatus: TaskStatus::Draft),
            GetPurchaseList::class,
            Method::GET,
            '/ExternalApi/v2/purchaseList',
            ['RestockReceivedStatus' => 'DRAFT', 'page' => 1, 'limit' => 100],
            null,
        ],
        'purchaseList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchaseList()->paginate()->current(),
            GetPurchaseList::class,
            Method::GET,
            '/ExternalApi/v2/purchaseList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetPurchaseList::class => [
            GetPurchaseList::class,
            [],
            Cin7Payloads::load('purchaseList', 'get.response'),
            PurchaseListData::class,
            'PurchaseList',
        ],
    ],
    'missing' => [
        'purchase list row without Type' => [PurchaseListData::class, Arr::except(Cin7Payloads::load('purchaseList', 'get.response')['PurchaseList'][0], 'Type')],
    ],
    'required' => [
        PurchaseListData::class => ['CombinedReceivingStatus', 'CombinedInvoiceStatus', 'CombinedPaymentStatus', 'Type'],
    ],
];
