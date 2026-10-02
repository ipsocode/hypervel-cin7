<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\PurchaseCreditNoteList\PurchaseCreditNoteListData;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\PurchaseCreditNoteList\GetPurchaseCreditNoteList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchaseCreditNoteList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetPurchaseCreditNoteList::class => [
            GetPurchaseCreditNoteList::class,
            ['creditNoteStatus' => TaskStatus::Authorised, 'status' => 'COMPLETED / CREDIT NOTE CLOSED'],
            Method::GET,
            '/ExternalApi/v2/purchaseCreditNoteList',
            ['CreditNoteStatus' => 'AUTHORISED', 'Status' => 'COMPLETED / CREDIT NOTE CLOSED', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'purchaseCreditNoteList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchaseCreditNoteList()->get(search: 'PO-00004'),
            GetPurchaseCreditNoteList::class,
            Method::GET,
            '/ExternalApi/v2/purchaseCreditNoteList',
            ['Search' => 'PO-00004', 'page' => 1, 'limit' => 100],
            null,
        ],
        'purchaseCreditNoteList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchaseCreditNoteList()->paginate(limit: 50)->current(),
            GetPurchaseCreditNoteList::class,
            Method::GET,
            '/ExternalApi/v2/purchaseCreditNoteList',
            ['limit' => 50, 'page' => 1],
            null,
        ],
    ],
    'dtos' => [
        GetPurchaseCreditNoteList::class => [
            GetPurchaseCreditNoteList::class,
            [],
            Cin7Payloads::load('purchaseCreditNoteList', 'get.response'),
            PurchaseCreditNoteListData::class,
            'PurchaseList',
        ],
    ],
    'missing' => [
        'purchase credit note list row without CombinedReceivingStatus' => [PurchaseCreditNoteListData::class, Arr::except(Cin7Payloads::load('purchaseCreditNoteList', 'get.response')['PurchaseList'][1], 'CombinedReceivingStatus')],
    ],
    'required' => [
        PurchaseCreditNoteListData::class => ['CombinedReceivingStatus', 'CombinedInvoiceStatus', 'CombinedPaymentStatus', 'Type'],
    ],
];
