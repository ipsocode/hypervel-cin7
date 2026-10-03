<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNotePostData;
use Ipsocode\Cin7\Requests\Purchase\CreditNote\GetPurchaseCreditNote;
use Ipsocode\Cin7\Requests\Purchase\CreditNote\PostPurchaseCreditNote;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/creditnote`; tests/Catalogue.php merges every file's rows by kind.

// The fields every credit note POST requires.
$fields = ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'CreditNoteNumber' => 'tr12', 'CreditNoteDate' => '2017-12-21T00:00:00', 'Status' => 'DRAFT', 'Lines' => [], 'Unstock' => []];

// One line, one additional charge and one unstock line, with every field each requires; the
// unstock line also carries the read-only fields the POST example sends.
$line = ['ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 1.0, 'Price' => 2.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports', 'Account' => '715', 'Total' => 2.0];
$charge = ['Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports', 'Account' => '715'];
$unstock = ['CardID' => 'a2db08f1-32cd-48e3-b558-a74af15ee53f', 'Date' => '2017-12-11T00:00:00', 'Quantity' => 3.0, 'ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Location' => 'Main Warehouse', 'BatchSN' => 'PO-00001-1', 'ExpiryDate' => '2017-11-28T00:00:00'];

// The POST example as sent: its unstock lines without their read-only fields.
$sent = Cin7Payloads::load('purchase/creditnote', 'post.request');
$sent['Unstock'] = array_map(static fn (array $item): array => Arr::only($item, ['CardID', 'Date', 'Quantity']), $sent['Unstock']);

return [
    'requests' => [
        GetPurchaseCreditNote::class => [
            GetPurchaseCreditNote::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/purchase/creditnote',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostPurchaseCreditNote::class => [
            PostPurchaseCreditNote::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/purchase/creditnote',
            [],
            $fields,
        ],
        PostPurchaseCreditNote::class . ' with data' => [
            PostPurchaseCreditNote::class,
            [fn (): PurchaseCreditNotePostData => PurchaseCreditNotePostData::from([...$fields, 'Lines' => [$line], 'AdditionalCharges' => [$charge], 'Unstock' => [$unstock]])],
            Method::POST,
            '/ExternalApi/v2/purchase/creditnote',
            [],
            [
                'Status' => 'DRAFT',
                'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136',
                'CombineAdditionalCharges' => false,
                'CreditNoteDate' => '2017-12-21T00:00:00',
                'AdditionalCharges' => [['Account' => '715', 'Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
                'CreditNoteNumber' => 'tr12',
                'Lines' => [['Account' => '715', 'Total' => 2.0, 'ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 1.0, 'Price' => 2.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
                'Unstock' => [['CardID' => 'a2db08f1-32cd-48e3-b558-a74af15ee53f', 'Quantity' => 3.0, 'Date' => '2017-12-11T00:00:00']],
            ],
        ],
    ],
    'resources' => [
        'purchase creditNote get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->creditNote()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchaseCreditNote::class,
            Method::GET,
            '/ExternalApi/v2/purchase/creditnote',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase creditNote get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->creditNote()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136', combineAdditionalCharges: true),
            GetPurchaseCreditNote::class,
            Method::GET,
            '/ExternalApi/v2/purchase/creditnote',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        'purchase creditNote post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->creditNote()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136']),
            PostPurchaseCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/purchase/creditnote',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
        ],
        'purchase creditNote post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->creditNote()->post(PurchaseCreditNotePostData::from([...$fields, 'Status' => 'AUTHORISED'])),
            PostPurchaseCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/purchase/creditnote',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'CreditNoteDate' => '2017-12-21T00:00:00', 'CreditNoteNumber' => 'tr12', 'Lines' => [], 'Unstock' => []],
        ],
    ],
    'dtos' => [
        GetPurchaseCreditNote::class => [GetPurchaseCreditNote::class, ['task-1'], Cin7Payloads::load('purchase/creditnote', 'get.response'), PurchaseCreditNoteData::class, ''],
        PostPurchaseCreditNote::class => [PostPurchaseCreditNote::class, [[]], Cin7Payloads::load('purchase/creditnote', 'post.response'), PurchaseCreditNoteData::class, ''],
    ],
    'bodies' => [
        PurchaseCreditNotePostData::class => [PurchaseCreditNotePostData::class, Cin7Payloads::load('purchase/creditnote', 'post.request')],
    ],
    'missing' => [
        'purchase credit note POST without TaskID' => [PurchaseCreditNotePostData::class, Arr::except(Cin7Payloads::load('purchase/creditnote', 'post.request'), 'TaskID')],
        'purchase credit note without Unstock' => [PurchaseCreditNoteData::class, Arr::except(Cin7Payloads::load('purchase/creditnote', 'get.response'), 'Unstock')],
        'purchase unstock line without CardID' => [PurchaseUnStockLineData::class, Arr::except($unstock, 'CardID')],
    ],
    'required' => [
        PurchaseCreditNoteData::class => ['CreditNoteNumber', 'Status', 'Lines', 'Unstock', 'CreditNoteDate'],
        PurchaseCreditNotePostData::class => ['CreditNoteNumber', 'Status', 'Lines', 'Unstock', 'TaskID', 'CombineAdditionalCharges', 'CreditNoteDate'],
        PurchaseUnStockLineData::class => ['CardID', 'Quantity'],
    ],
    'omitted' => [
        PostPurchaseCreditNote::class => [PostPurchaseCreditNote::class, Cin7Payloads::load('purchase/creditnote', 'post.request'), $sent],
    ],
];
