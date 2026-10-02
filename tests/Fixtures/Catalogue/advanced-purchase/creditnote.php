<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNotesData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchasePartialCreditNoteData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchasePartialCreditNotePostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\DeleteAdvancedPurchaseCreditNote;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\GetAdvancedPurchaseCreditNote;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\PostAdvancedPurchaseCreditNote;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/creditnote`; tests/Catalogue.php merges every file's rows by kind.

// The fields every credit note POST requires; the empty-GUID `TaskID` creates a credit note.
$fields = ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteNumber' => 'CR-00103/1', 'CreditNoteInvoiceNumber' => 'INV-00103/2', 'CreditNoteDate' => '2018-04-15T00:00:00', 'Status' => 'DRAFT', 'Lines' => [], 'Unstock' => []];

// One line, one additional charge and one unstock line, with every field each requires; the
// unstock line also carries the read-only fields the POST example sends.
$line = ['ProductID' => '11510572-0f9e-4d7c-a203-7e0563c3388f', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 5.0, 'Price' => 15.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases', 'Account' => '715', 'Total' => 75.0];
$charge = ['Description' => 'Rounding', 'Quantity' => 1.0, 'Price' => 0.5, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases', 'Account' => '715'];
$unstock = ['CardID' => '0c6555ab-2cf1-4939-871d-8060b9bbfa38', 'Date' => '2018-04-15T00:00:00', 'Quantity' => 5.0, 'ProductID' => '11510572-0f9e-4d7c-a203-7e0563c3388f', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Location' => 'Main Warehouse', 'BatchSN' => '658487', 'ExpiryDate' => '2018-06-01T00:00:00'];

// The POST example as sent: its unstock lines without their read-only fields.
$sent = Cin7Payloads::load('advanced-purchase/creditnote', 'post.request');
$sent['Unstock'] = array_map(static fn (array $item): array => Arr::only($item, ['CardID', 'Date', 'Quantity']), $sent['Unstock']);

return [
    'requests' => [
        GetAdvancedPurchaseCreditNote::class => [
            GetAdvancedPurchaseCreditNote::class,
            ['5a7fb526-527a-4229-b331-90b6f5535aab', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostAdvancedPurchaseCreditNote::class => [
            PostAdvancedPurchaseCreditNote::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            [],
            $fields,
        ],
        DeleteAdvancedPurchaseCreditNote::class => [
            DeleteAdvancedPurchaseCreditNote::class,
            ['43ce7d3b-8c67-4aff-9224-b36b95811b08'],
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            ['TaskID' => '43ce7d3b-8c67-4aff-9224-b36b95811b08'],
            null,
        ],
        PostAdvancedPurchaseCreditNote::class . ' with data' => [
            PostAdvancedPurchaseCreditNote::class,
            [fn (): AdvancedPurchasePartialCreditNotePostData => AdvancedPurchasePartialCreditNotePostData::from([...$fields, 'Lines' => [$line], 'AdditionalCharges' => [$charge], 'Unstock' => [$unstock]])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            [],
            [
                'Status' => 'DRAFT',
                'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab',
                'TaskID' => '00000000-0000-0000-0000-000000000000',
                'CombineAdditionalCharges' => false,
                'CreditNoteInvoiceNumber' => 'INV-00103/2',
                'AdditionalCharges' => [['Account' => '715', 'Description' => 'Rounding', 'Quantity' => 1.0, 'Price' => 0.5, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases']],
                'CreditNoteNumber' => 'CR-00103/1',
                'CreditNoteDate' => '2018-04-15T00:00:00',
                'Lines' => [['Account' => '715', 'Total' => 75.0, 'ProductID' => '11510572-0f9e-4d7c-a203-7e0563c3388f', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 5.0, 'Price' => 15.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases']],
                'Unstock' => [['CardID' => '0c6555ab-2cf1-4939-871d-8060b9bbfa38', 'Quantity' => 5.0, 'Date' => '2018-04-15T00:00:00']],
            ],
        ],
    ],
    'resources' => [
        'advancedPurchase creditNote get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->creditNote()->get('5a7fb526-527a-4229-b331-90b6f5535aab'),
            GetAdvancedPurchaseCreditNote::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        'advancedPurchase creditNote get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->creditNote()->get('5a7fb526-527a-4229-b331-90b6f5535aab', combineAdditionalCharges: false),
            GetAdvancedPurchaseCreditNote::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'CombineAdditionalCharges' => 'false'],
            null,
        ],
        'advancedPurchase creditNote post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->creditNote()->post(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '00000000-0000-0000-0000-000000000000']),
            PostAdvancedPurchaseCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '00000000-0000-0000-0000-000000000000'],
        ],
        'advancedPurchase creditNote post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->creditNote()->post(AdvancedPurchasePartialCreditNotePostData::from([...$fields, 'Status' => 'AUTHORISED'])),
            PostAdvancedPurchaseCreditNote::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'CreditNoteInvoiceNumber' => 'INV-00103/2', 'CreditNoteNumber' => 'CR-00103/1', 'CreditNoteDate' => '2018-04-15T00:00:00', 'Lines' => [], 'Unstock' => []],
        ],
        'advancedPurchase creditNote delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->creditNote()->delete('43ce7d3b-8c67-4aff-9224-b36b95811b08'),
            DeleteAdvancedPurchaseCreditNote::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/creditnote',
            ['TaskID' => '43ce7d3b-8c67-4aff-9224-b36b95811b08'],
            null,
        ],
    ],
    'dtos' => [
        GetAdvancedPurchaseCreditNote::class => [GetAdvancedPurchaseCreditNote::class, ['purchase-1'], Cin7Payloads::load('advanced-purchase/creditnote', 'get.response'), AdvancedPurchaseCreditNotesData::class, ''],
        PostAdvancedPurchaseCreditNote::class => [PostAdvancedPurchaseCreditNote::class, [[]], Cin7Payloads::load('advanced-purchase/creditnote', 'post.response'), AdvancedPurchaseCreditNotesData::class, ''],
        DeleteAdvancedPurchaseCreditNote::class => [DeleteAdvancedPurchaseCreditNote::class, ['task-1'], Cin7Payloads::load('advanced-purchase/creditnote', 'delete.response'), AdvancedPurchaseCreditNotesData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePartialCreditNotePostData::class => [AdvancedPurchasePartialCreditNotePostData::class, Cin7Payloads::load('advanced-purchase/creditnote', 'post.request')],
    ],
    'missing' => [
        'advanced purchase credit note envelope without CreditNotes' => [AdvancedPurchaseCreditNotesData::class, Arr::except(Cin7Payloads::load('advanced-purchase/creditnote', 'get.response'), 'CreditNotes')],
        'advanced purchase credit note without CreditNoteInvoiceNumber' => [AdvancedPurchasePartialCreditNoteData::class, Arr::except(Cin7Payloads::load('advanced-purchase/creditnote', 'get.response')['CreditNotes'][0], 'CreditNoteInvoiceNumber')],
        'advanced purchase credit note POST without PurchaseID' => [AdvancedPurchasePartialCreditNotePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/creditnote', 'post.request'), 'PurchaseID')],
        'advanced purchase credit note POST without TaskID' => [AdvancedPurchasePartialCreditNotePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/creditnote', 'post.request'), 'TaskID')],
    ],
    'required' => [
        AdvancedPurchaseCreditNotesData::class => ['PurchaseID', 'CreditNotes'],
        AdvancedPurchasePartialCreditNoteData::class => ['CreditNoteNumber', 'CreditNoteDate', 'Status', 'Lines', 'Unstock', 'TaskID', 'CombineAdditionalCharges', 'CreditNoteInvoiceNumber'],
        AdvancedPurchasePartialCreditNotePostData::class => ['CreditNoteNumber', 'CreditNoteDate', 'Status', 'Lines', 'Unstock', 'PurchaseID', 'TaskID', 'CombineAdditionalCharges', 'CreditNoteInvoiceNumber'],
    ],
    'omitted' => [
        PostAdvancedPurchaseCreditNote::class => [PostAdvancedPurchaseCreditNote::class, Cin7Payloads::load('advanced-purchase/creditnote', 'post.request'), $sent],
    ],
];
