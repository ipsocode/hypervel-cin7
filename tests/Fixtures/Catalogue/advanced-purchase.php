<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePutData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoiceData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\DeleteAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\GetAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PostAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAdvancedPurchase;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase`; tests/Catalogue.php merges every file's rows by kind.

// The fields every advanced purchase POST requires, and a supplier.
$fields = ['Supplier' => 'ABPA', 'Approach' => 'STOCK', 'Location' => 'Main Warehouse'];

// A shipping address with the fields its table requires and the purchase's own.
$address = ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA', 'City' => 'Melbourne', 'ShipToOther' => false];

// The GET example, whose embedded documents the missing rows take apart.
$purchase = Cin7Payloads::load('advanced-purchase', 'get.response');

return [
    'requests' => [
        GetAdvancedPurchase::class => [
            GetAdvancedPurchase::class,
            ['78f64a2d-f339-4f0d-b29e-28646d05093d', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '78f64a2d-f339-4f0d-b29e-28646d05093d', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostAdvancedPurchase::class => [
            PostAdvancedPurchase::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase',
            [],
            $fields,
        ],
        PutAdvancedPurchase::class => [
            PutAdvancedPurchase::class,
            [['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Note' => 'Rush']],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase',
            [],
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Note' => 'Rush'],
        ],
        DeleteAdvancedPurchase::class => [
            DeleteAdvancedPurchase::class,
            ['695dbaf4-92c3-4388-a35c-0efa378db93e', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Void' => 'true'],
            null,
        ],
        PostAdvancedPurchase::class . ' with data' => [
            PostAdvancedPurchase::class,
            [fn (): AdvancedPurchasePostData => AdvancedPurchasePostData::from([...$fields, 'PurchaseType' => 'Advanced', 'TaxCalculation' => 'Exclusive', 'ShippingAddress' => $address])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase',
            [],
            [
                'Approach' => 'STOCK',
                'PurchaseType' => 'Advanced',
                'Supplier' => 'ABPA',
                'ShippingAddress' => ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA', 'ShipToOther' => false, 'City' => 'Melbourne'],
                'TaxCalculation' => 'Exclusive',
                'Location' => 'Main Warehouse',
            ],
        ],
        PutAdvancedPurchase::class . ' with data' => [
            PutAdvancedPurchase::class,
            [fn (): AdvancedPurchasePutData => AdvancedPurchasePutData::from(['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'SupplierID' => '92c27d86-a8d3-4335-9da1-d3ebd82cb568', 'IsServiceOnly' => false, 'Location' => 'Main Warehouse'])],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase',
            [],
            [
                'ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e',
                'IsServiceOnly' => false,
                'SupplierID' => '92c27d86-a8d3-4335-9da1-d3ebd82cb568',
                'Location' => 'Main Warehouse',
            ],
        ],
    ],
    'resources' => [
        'advancedPurchase get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->get('78f64a2d-f339-4f0d-b29e-28646d05093d'),
            GetAdvancedPurchase::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '78f64a2d-f339-4f0d-b29e-28646d05093d'],
            null,
        ],
        'advancedPurchase get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->get('78f64a2d-f339-4f0d-b29e-28646d05093d', combineAdditionalCharges: false),
            GetAdvancedPurchase::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '78f64a2d-f339-4f0d-b29e-28646d05093d', 'CombineAdditionalCharges' => 'false'],
            null,
        ],
        'advancedPurchase post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->post(['Supplier' => 'ABPA']),
            PostAdvancedPurchase::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase',
            [],
            ['Supplier' => 'ABPA'],
        ],
        'advancedPurchase post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->post(AdvancedPurchasePostData::from($fields)),
            PostAdvancedPurchase::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase',
            [],
            ['Approach' => 'STOCK', 'Supplier' => 'ABPA', 'Location' => 'Main Warehouse'],
        ],
        'advancedPurchase put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->put(['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Note' => 'Rush']),
            PutAdvancedPurchase::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase',
            [],
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Note' => 'Rush'],
        ],
        'advancedPurchase put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->put(AdvancedPurchasePutData::from(['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', ...$fields, 'Note' => 'Rush'])),
            PutAdvancedPurchase::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase',
            [],
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Approach' => 'STOCK', 'Supplier' => 'ABPA', 'Note' => 'Rush', 'Location' => 'Main Warehouse'],
        ],
        'advancedPurchase delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->delete('695dbaf4-92c3-4388-a35c-0efa378db93e'),
            DeleteAdvancedPurchase::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e'],
            null,
        ],
        'advancedPurchase delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->delete('695dbaf4-92c3-4388-a35c-0efa378db93e', void: false),
            DeleteAdvancedPurchase::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase',
            ['ID' => '695dbaf4-92c3-4388-a35c-0efa378db93e', 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetAdvancedPurchase::class => [GetAdvancedPurchase::class, ['guid-1'], $purchase, AdvancedPurchaseData::class, ''],
        PostAdvancedPurchase::class => [PostAdvancedPurchase::class, [[]], Cin7Payloads::load('advanced-purchase', 'post.response'), AdvancedPurchaseData::class, ''],
        PutAdvancedPurchase::class => [PutAdvancedPurchase::class, [[]], Cin7Payloads::load('advanced-purchase', 'put.response'), AdvancedPurchaseData::class, ''],
        DeleteAdvancedPurchase::class => [DeleteAdvancedPurchase::class, ['guid-1'], Cin7Payloads::load('advanced-purchase', 'delete.response'), AdvancedPurchaseData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePostData::class => [AdvancedPurchasePostData::class, Cin7Payloads::load('advanced-purchase', 'post.request')],
        AdvancedPurchasePutData::class => [AdvancedPurchasePutData::class, Cin7Payloads::load('advanced-purchase', 'put.request')],
    ],
    'missing' => [
        'advanced purchase without Approach' => [AdvancedPurchaseData::class, Arr::except($purchase, 'Approach')],
        'advanced purchase without Location' => [AdvancedPurchaseData::class, Arr::except($purchase, 'Location')],
        'advanced purchase POST without Approach' => [AdvancedPurchasePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase', 'post.request'), 'Approach')],
        'advanced purchase POST without Location' => [AdvancedPurchasePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase', 'post.request'), 'Location')],
        'advanced purchase PUT without ID' => [AdvancedPurchasePutData::class, Arr::except(Cin7Payloads::load('advanced-purchase', 'put.request'), 'ID')],
        'advanced purchase PUT without Location' => [AdvancedPurchasePutData::class, Arr::except(Cin7Payloads::load('advanced-purchase', 'put.request'), 'Location')],
        'advanced purchase embedded invoice without TaskID' => [AdvancedPurchaseInvoiceData::class, Arr::except($purchase['Invoice'][0], 'TaskID')],
        'advanced purchase embedded invoice without Lines' => [AdvancedPurchaseInvoiceData::class, Arr::except($purchase['Invoice'][0], 'Lines')],
        'advanced purchase embedded credit note without TaskID' => [AdvancedPurchaseCreditNoteData::class, Arr::except($purchase['CreditNote'][0], 'TaskID')],
        'advanced purchase embedded credit note without Unstock' => [AdvancedPurchaseCreditNoteData::class, Arr::except($purchase['CreditNote'][0], 'Unstock')],
        'advanced purchase embedded manual journal without TaskID' => [AdvancedPurchaseManualJournalData::class, Arr::except($purchase['ManualJournals'][0], 'TaskID')],
    ],
    'required' => [
        AdvancedPurchaseData::class => ['Location', 'Approach'],
        AdvancedPurchasePostData::class => ['Location', 'Approach'],
        AdvancedPurchasePutData::class => ['Location', 'ID'],
        AdvancedPurchaseInvoiceData::class => ['Status', 'Lines', 'TaskID'],
        AdvancedPurchaseCreditNoteData::class => ['CreditNoteNumber', 'Status', 'Lines', 'Unstock', 'TaskID'],
        AdvancedPurchaseManualJournalData::class => ['Status', 'TaskID'],
    ],
    'omitted' => [
        PutAdvancedPurchase::class => [
            PutAdvancedPurchase::class,
            [...Cin7Payloads::load('advanced-purchase', 'put.request'), 'PurchaseType' => 'Advanced'],
            Cin7Payloads::load('advanced-purchase', 'put.request'),
        ],
    ],
];
