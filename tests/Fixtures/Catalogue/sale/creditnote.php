<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePartialData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\Sale\CreditNote\DeleteSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\GetSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\PostSaleCreditNote;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/creditnote`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        DeleteSaleCreditNote::class => [
            DeleteSaleCreditNote::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88', 'void' => false],
            Method::DELETE,
            '/ExternalApi/v2/sale/creditnote',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
            null,
        ],
        GetSaleCreditNote::class => [
            GetSaleCreditNote::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4', 'includePaymentInfo' => true],
            Method::GET,
            '/ExternalApi/v2/sale/creditnote',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludePaymentInfo' => 'true'],
            null,
        ],
        PostSaleCreditNote::class => [
            PostSaleCreditNote::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']],
            Method::POST,
            '/ExternalApi/v2/sale/creditnote',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        PostSaleCreditNote::class . ' with data' => [
            PostSaleCreditNote::class,
            [fn (): SaleCreditNotePostData => SaleCreditNotePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Memo' => 'Damaged', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00'])],
            Method::POST,
            '/ExternalApi/v2/sale/creditnote',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Status' => 'AUTHORISED', 'Memo' => 'Damaged', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CreditNoteDate' => '2017-11-22T00:00:00'],
        ],
    ],
    'resources' => [
        'sale creditNote get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->get('916ab4c0-6ccb-4c93-873d-0603859050e4', includePaymentInfo: true),
            GetSaleCreditNote::class,
            Method::GET,
            '/ExternalApi/v2/sale/creditnote',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludePaymentInfo' => 'true'],
            null,
        ],
        'sale creditNote post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
            PostSaleCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/sale/creditnote',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale creditNote post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->post(SaleCreditNotePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Memo' => 'Damaged', 'Status' => 'AUTHORISED', 'CreditNoteDate' => '2017-11-22T00:00:00'])),
            PostSaleCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/sale/creditnote',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00005', 'Status' => 'AUTHORISED', 'Memo' => 'Damaged', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CreditNoteDate' => '2017-11-22T00:00:00'],
        ],
        'sale creditNote delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->creditNote()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
            DeleteSaleCreditNote::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/creditnote',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
    ],
    'dtos' => [
        GetSaleCreditNote::class => [GetSaleCreditNote::class, ['sale-1'], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
        PostSaleCreditNote::class => [PostSaleCreditNote::class, [[]], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
        DeleteSaleCreditNote::class => [DeleteSaleCreditNote::class, ['task-1'], Cin7Payloads::saleCreditNotes(), SaleCreditNotesData::class, ''],
    ],
    'bodies' => [
        SaleCreditNotePostData::class => [SaleCreditNotePostData::class, Cin7Payloads::saleCreditNotePost()],
    ],
    'missing' => [
        'credit note POST without CreditNoteInvoiceNumber' => [
            SaleCreditNotePostData::class,
            Arr::except(Cin7Payloads::saleCreditNotePost(), 'CreditNoteInvoiceNumber'),
        ],
        'credit note without CreditNoteDate' => [SaleCreditNotePartialData::class, Arr::except(Cin7Payloads::saleCreditNotePartial(), 'CreditNoteDate')],
    ],
    'required' => [
        SaleCreditNotesData::class => ['SaleID'],
        SaleCreditNotePartialData::class => ['TaskID', 'CombineAdditionalCharges', 'Status', 'CreditNoteDate'],
        SaleCreditNotePostData::class => ['SaleID', 'TaskID', 'CombineAdditionalCharges', 'CreditNoteInvoiceNumber', 'Status', 'CreditNoteDate'],
    ],
];
