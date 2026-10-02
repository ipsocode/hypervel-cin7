<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoicePostData;
use Ipsocode\Cin7\Requests\Purchase\Invoice\GetPurchaseInvoice;
use Ipsocode\Cin7\Requests\Purchase\Invoice\PostPurchaseInvoice;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/invoice`; tests/Catalogue.php merges every file's rows by kind.

// The fields every invoice POST requires.
$fields = ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'InvoiceDate' => '2017-12-14T00:00:00', 'InvoiceDueDate' => '2018-01-13T00:00:00', 'Status' => 'DRAFT', 'Lines' => []];

// One line and one additional charge, with every field each requires.
$line = ['ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 2.0, 'Price' => 2.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports', 'Account' => '715', 'Total' => 4.0];
$charge = ['Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports', 'Account' => '715'];

return [
    'requests' => [
        GetPurchaseInvoice::class => [
            GetPurchaseInvoice::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/purchase/invoice',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostPurchaseInvoice::class => [
            PostPurchaseInvoice::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/purchase/invoice',
            [],
            $fields,
        ],
        PostPurchaseInvoice::class . ' with data' => [
            PostPurchaseInvoice::class,
            [fn (): PurchaseInvoicePostData => PurchaseInvoicePostData::from([...$fields, 'InvoiceNumber' => 'INV-1', 'Lines' => [$line], 'AdditionalCharges' => [$charge]])],
            Method::POST,
            '/ExternalApi/v2/purchase/invoice',
            [],
            [
                'Status' => 'DRAFT',
                'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136',
                'CombineAdditionalCharges' => false,
                'InvoiceDate' => '2017-12-14T00:00:00',
                'InvoiceDueDate' => '2018-01-13T00:00:00',
                'InvoiceNumber' => 'INV-1',
                'AdditionalCharges' => [['Account' => '715', 'Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
                'Lines' => [['Account' => '715', 'Total' => 4.0, 'ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 2.0, 'Price' => 2.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
            ],
        ],
    ],
    'resources' => [
        'purchase invoice get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->invoice()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchaseInvoice::class,
            Method::GET,
            '/ExternalApi/v2/purchase/invoice',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase invoice get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->invoice()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136', combineAdditionalCharges: true),
            GetPurchaseInvoice::class,
            Method::GET,
            '/ExternalApi/v2/purchase/invoice',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        'purchase invoice post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->invoice()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136']),
            PostPurchaseInvoice::class,
            Method::POST,
            '/ExternalApi/v2/purchase/invoice',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
        ],
        'purchase invoice post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->invoice()->post(PurchaseInvoicePostData::from([...$fields, 'Status' => 'AUTHORISED'])),
            PostPurchaseInvoice::class,
            Method::POST,
            '/ExternalApi/v2/purchase/invoice',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'InvoiceDate' => '2017-12-14T00:00:00', 'InvoiceDueDate' => '2018-01-13T00:00:00', 'Lines' => []],
        ],
    ],
    'dtos' => [
        GetPurchaseInvoice::class => [GetPurchaseInvoice::class, ['task-1'], Cin7Payloads::load('purchase/invoice', 'get.response'), PurchaseInvoiceData::class, ''],
        PostPurchaseInvoice::class => [PostPurchaseInvoice::class, [[]], Cin7Payloads::load('purchase/invoice', 'post.response'), PurchaseInvoiceData::class, ''],
    ],
    'bodies' => [
        PurchaseInvoicePostData::class => [PurchaseInvoicePostData::class, Cin7Payloads::load('purchase/invoice', 'post.request')],
    ],
    'missing' => [
        'purchase invoice POST without TaskID' => [PurchaseInvoicePostData::class, Arr::except(Cin7Payloads::load('purchase/invoice', 'post.request'), 'TaskID')],
        'purchase invoice POST without InvoiceDueDate' => [PurchaseInvoicePostData::class, Arr::except(Cin7Payloads::load('purchase/invoice', 'post.request'), 'InvoiceDueDate')],
        'purchase invoice without Status' => [PurchaseInvoiceData::class, Arr::except(Cin7Payloads::load('purchase/invoice', 'get.response'), 'Status')],
        'purchase invoice line without Account' => [PurchaseInvoiceLineData::class, Arr::except($line, 'Account')],
        'purchase invoice charge without Account' => [PurchaseInvoiceAdditionalChargeData::class, Arr::except($charge, 'Account')],
    ],
    'required' => [
        PurchaseInvoiceData::class => ['Status', 'Lines', 'InvoiceDate'],
        PurchaseInvoicePostData::class => ['Status', 'Lines', 'TaskID', 'CombineAdditionalCharges', 'InvoiceDate', 'InvoiceDueDate'],
        PurchaseInvoiceLineData::class => ['ProductID', 'SKU', 'Name', 'Quantity', 'Price', 'Tax', 'TaxRule', 'Account', 'Total'],
        PurchaseInvoiceAdditionalChargeData::class => ['Description', 'Quantity', 'Price', 'Tax', 'TaxRule', 'Account'],
    ],
];
