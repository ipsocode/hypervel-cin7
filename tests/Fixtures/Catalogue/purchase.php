<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\CreditNote\SimplePurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Purchase\Invoice\SimplePurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\PurchaseData;
use Ipsocode\Cin7\Data\Purchase\PurchasePostData;
use Ipsocode\Cin7\Data\Purchase\PurchasePutData;
use Ipsocode\Cin7\Data\Purchase\PurchaseUnStockData;
use Ipsocode\Cin7\Requests\Purchase\DeletePurchase;
use Ipsocode\Cin7\Requests\Purchase\GetPurchase;
use Ipsocode\Cin7\Requests\Purchase\PostPurchase;
use Ipsocode\Cin7\Requests\Purchase\PutPurchase;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase`; tests/Catalogue.php merges every file's rows by kind.

// The fields every purchase write requires, and a supplier.
$fields = ['Supplier' => 'ABPA', 'Approach' => 'INVOICE', 'Location' => 'Main Warehouse'];

// A shipping address with the fields its table requires and the purchase's own.
$address = ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA', 'City' => 'Melbourne', 'ShipToOther' => false];

// The embedded documents of the GET example, which the purchase's own classes read.
$purchase = Cin7Payloads::load('purchase', 'get.response');

return [
    'requests' => [
        GetPurchase::class => [
            GetPurchase::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/purchase',
            ['ID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostPurchase::class => [
            PostPurchase::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/purchase',
            [],
            $fields,
        ],
        PutPurchase::class => [
            PutPurchase::class,
            [['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Note' => 'Rush']],
            Method::PUT,
            '/ExternalApi/v2/purchase',
            [],
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Note' => 'Rush'],
        ],
        DeletePurchase::class => [
            DeletePurchase::class,
            ['3fb1debd-1f89-476c-b7ac-826a493a2092', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/purchase',
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Void' => 'true'],
            null,
        ],
        PostPurchase::class . ' with data' => [
            PostPurchase::class,
            [fn (): PurchasePostData => PurchasePostData::from([...$fields, 'TaxCalculation' => 'Exclusive', 'ShippingAddress' => $address])],
            Method::POST,
            '/ExternalApi/v2/purchase',
            [],
            [
                'Supplier' => 'ABPA',
                'ShippingAddress' => ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA', 'ShipToOther' => false, 'City' => 'Melbourne'],
                'TaxCalculation' => 'Exclusive',
                'Approach' => 'INVOICE',
                'Location' => 'Main Warehouse',
            ],
        ],
        PutPurchase::class . ' with data' => [
            PutPurchase::class,
            [fn (): PurchasePutData => PurchasePutData::from(['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'SupplierID' => 'f1d1696b-8988-4ca0-8b9d-60317e463d07', 'BillingAddress' => ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA'], 'Approach' => 'STOCK', 'Location' => 'Main Warehouse'])],
            Method::PUT,
            '/ExternalApi/v2/purchase',
            [],
            [
                'ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092',
                'SupplierID' => 'f1d1696b-8988-4ca0-8b9d-60317e463d07',
                'BillingAddress' => ['Line1' => '3 Park Street Industrial Village', 'Country' => 'USA'],
                'Approach' => 'STOCK',
                'Location' => 'Main Warehouse',
            ],
        ],
    ],
    'resources' => [
        'purchase get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchase::class,
            Method::GET,
            '/ExternalApi/v2/purchase',
            ['ID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136', combineAdditionalCharges: false),
            GetPurchase::class,
            Method::GET,
            '/ExternalApi/v2/purchase',
            ['ID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'false'],
            null,
        ],
        'purchase post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->post(['Supplier' => 'ABPA']),
            PostPurchase::class,
            Method::POST,
            '/ExternalApi/v2/purchase',
            [],
            ['Supplier' => 'ABPA'],
        ],
        'purchase put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->put(['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Note' => 'Rush']),
            PutPurchase::class,
            Method::PUT,
            '/ExternalApi/v2/purchase',
            [],
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Note' => 'Rush'],
        ],
        'purchase delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->delete('3fb1debd-1f89-476c-b7ac-826a493a2092'),
            DeletePurchase::class,
            Method::DELETE,
            '/ExternalApi/v2/purchase',
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092'],
            null,
        ],
        'purchase delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->delete('3fb1debd-1f89-476c-b7ac-826a493a2092', void: false),
            DeletePurchase::class,
            Method::DELETE,
            '/ExternalApi/v2/purchase',
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Void' => 'false'],
            null,
        ],
        'purchase post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->post(PurchasePostData::from($fields)),
            PostPurchase::class,
            Method::POST,
            '/ExternalApi/v2/purchase',
            [],
            $fields,
        ],
        'purchase put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->put(PurchasePutData::from(['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', ...$fields, 'Note' => 'Rush'])),
            PutPurchase::class,
            Method::PUT,
            '/ExternalApi/v2/purchase',
            [],
            ['ID' => '3fb1debd-1f89-476c-b7ac-826a493a2092', 'Supplier' => 'ABPA', 'Note' => 'Rush', 'Approach' => 'INVOICE', 'Location' => 'Main Warehouse'],
        ],
    ],
    'dtos' => [
        GetPurchase::class => [GetPurchase::class, ['guid-1'], $purchase, PurchaseData::class, ''],
        PostPurchase::class => [PostPurchase::class, [[]], Cin7Payloads::load('purchase', 'post.response'), PurchaseData::class, ''],
        PutPurchase::class => [PutPurchase::class, [[]], Cin7Payloads::load('purchase', 'put.response'), PurchaseData::class, ''],
        DeletePurchase::class => [DeletePurchase::class, ['guid-1'], Cin7Payloads::load('purchase', 'delete.response'), PurchaseData::class, ''],
    ],
    'bodies' => [
        PurchasePostData::class => [PurchasePostData::class, Cin7Payloads::load('purchase', 'post.request')],
        PurchasePutData::class => [PurchasePutData::class, Cin7Payloads::load('purchase', 'put.request')],
    ],
    'missing' => [
        'purchase without Approach' => [PurchaseData::class, Arr::except($purchase, 'Approach')],
        'purchase POST without Location' => [PurchasePostData::class, Arr::except(Cin7Payloads::load('purchase', 'post.request'), 'Location')],
        'purchase PUT without ID' => [PurchasePutData::class, Arr::except(Cin7Payloads::load('purchase', 'put.request'), 'ID')],
        'simple purchase invoice without InvoiceDate' => [SimplePurchaseInvoiceData::class, Arr::except($purchase['Invoice'], 'InvoiceDate')],
        'simple purchase credit note without Unstock' => [SimplePurchaseCreditNoteData::class, Arr::except($purchase['CreditNote'], 'Unstock')],
        'purchase unstock without Lines' => [PurchaseUnStockData::class, Arr::except($purchase['CreditNote']['Unstock'], 'Lines')],
    ],
    'required' => [
        PurchaseData::class => ['Approach', 'Location'],
        PurchasePostData::class => ['Approach', 'Location'],
        PurchasePutData::class => ['Approach', 'Location', 'ID'],
        SimplePurchaseInvoiceData::class => ['InvoiceDate', 'Status', 'Lines'],
        SimplePurchaseCreditNoteData::class => ['CreditNoteNumber', 'Status', 'Lines', 'Unstock'],
        PurchaseUnStockData::class => ['Status', 'Lines'],
    ],
];
