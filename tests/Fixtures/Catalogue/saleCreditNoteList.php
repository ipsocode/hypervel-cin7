<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\SaleCreditNoteList\SaleCreditNoteListData;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\SaleCreditNoteList\GetSaleCreditNoteList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `saleCreditNoteList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSaleCreditNoteList::class => [
            GetSaleCreditNoteList::class,
            ['creditNoteStatus' => TaskStatus::Authorised, 'status' => SaleStatus::Credited],
            Method::GET,
            '/ExternalApi/v2/saleCreditNoteList',
            ['CreditNoteStatus' => 'AUTHORISED', 'Status' => 'CREDITED', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'saleCreditNoteList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->saleCreditNoteList()->get(search: 'CR-00002'),
            GetSaleCreditNoteList::class,
            Method::GET,
            '/ExternalApi/v2/saleCreditNoteList',
            ['Search' => 'CR-00002', 'page' => 1, 'limit' => 100],
            null,
        ],
        'saleCreditNoteList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->saleCreditNoteList()->paginate(limit: 50)->current(),
            GetSaleCreditNoteList::class,
            Method::GET,
            '/ExternalApi/v2/saleCreditNoteList',
            ['limit' => 50, 'page' => 1],
            null,
        ],
    ],
    'dtos' => [
        GetSaleCreditNoteList::class => [
            GetSaleCreditNoteList::class,
            [],
            Cin7Payloads::load('saleCreditNoteList', 'get.response'),
            SaleCreditNoteListData::class,
            'SaleList',
        ],
    ],
    'missing' => [
        'credit note list row without QuoteStatus' => [SaleCreditNoteListData::class, Arr::except(Cin7Payloads::load('saleCreditNoteList', 'get.response')['SaleList'][1], 'QuoteStatus')],
    ],
    'required' => [
        SaleCreditNoteListData::class => ['SaleID', 'OrderNumber', 'Status', 'OrderDate', 'Customer', 'InvoiceAmount', 'PaidAmount', 'BaseCurrency', 'CustomerCurrency', 'Updated', 'OrderStatus', 'CombinedPickingStatus', 'CombinedPackingStatus', 'CombinedShippingStatus', 'FulFilmentStatus', 'CombinedInvoiceStatus', 'CreditNoteStatus', 'CombinedPaymentStatus', 'Type', 'QuoteStatus'],
    ],
];
