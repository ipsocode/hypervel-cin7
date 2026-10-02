<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoicesData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchasePartialInvoiceData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchasePartialInvoicePostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\DeleteAdvancedPurchaseInvoice;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\GetAdvancedPurchaseInvoice;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\PostAdvancedPurchaseInvoice;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/invoice`; tests/Catalogue.php merges every file's rows by kind.

// The fields every advanced purchase invoice POST requires.
$fields = ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'CombineAdditionalCharges' => false, 'InvoiceDate' => '2018-04-23T00:00:00', 'InvoiceDueDate' => '2018-05-23T00:00:00', 'Status' => 'DRAFT', 'Lines' => []];

// One line and one additional charge, with every field each requires.
$line = ['ProductID' => '11510572-0f9e-4d7c-a203-7e0563c3388f', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 6.0, 'Price' => 15.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases', 'Account' => '715', 'Total' => 90.0];
$charge = ['Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases', 'Account' => '715'];

return [
    'requests' => [
        GetAdvancedPurchaseInvoice::class => [
            GetAdvancedPurchaseInvoice::class,
            ['5a7fb526-527a-4229-b331-90b6f5535aab', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostAdvancedPurchaseInvoice::class => [
            PostAdvancedPurchaseInvoice::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/invoice',
            [],
            $fields,
        ],
        DeleteAdvancedPurchaseInvoice::class => [
            DeleteAdvancedPurchaseInvoice::class,
            ['3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Void' => 'true'],
            null,
        ],
        PostAdvancedPurchaseInvoice::class . ' with data' => [
            PostAdvancedPurchaseInvoice::class,
            [fn (): AdvancedPurchasePartialInvoicePostData => AdvancedPurchasePartialInvoicePostData::from([...$fields, 'InvoiceNumber' => 'INV-00103', 'Lines' => [$line], 'AdditionalCharges' => [$charge]])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/invoice',
            [],
            [
                'Status' => 'DRAFT',
                'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab',
                'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f',
                'CombineAdditionalCharges' => false,
                'InvoiceDate' => '2018-04-23T00:00:00',
                'InvoiceDueDate' => '2018-05-23T00:00:00',
                'InvoiceNumber' => 'INV-00103',
                'AdditionalCharges' => [['Account' => '715', 'Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases']],
                'Lines' => [['Account' => '715', 'Total' => 90.0, 'ProductID' => '11510572-0f9e-4d7c-a203-7e0563c3388f', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 6.0, 'Price' => 15.0, 'Tax' => 0.0, 'TaxRule' => 'Tax on Purchases']],
            ],
        ],
    ],
    'resources' => [
        'advancedPurchase invoice get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->get('5a7fb526-527a-4229-b331-90b6f5535aab'),
            GetAdvancedPurchaseInvoice::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        'advancedPurchase invoice get with combined charges' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->get('5a7fb526-527a-4229-b331-90b6f5535aab', combineAdditionalCharges: false),
            GetAdvancedPurchaseInvoice::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'CombineAdditionalCharges' => 'false'],
            null,
        ],
        'advancedPurchase invoice post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->post($fields),
            PostAdvancedPurchaseInvoice::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/invoice',
            [],
            $fields,
        ],
        'advancedPurchase invoice post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->post(AdvancedPurchasePartialInvoicePostData::from([...$fields, 'Status' => 'AUTHORISED'])),
            PostAdvancedPurchaseInvoice::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/invoice',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'CombineAdditionalCharges' => false, 'InvoiceDate' => '2018-04-23T00:00:00', 'InvoiceDueDate' => '2018-05-23T00:00:00', 'Lines' => []],
        ],
        'advancedPurchase invoice delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->delete('3320ef94-a7e8-4d81-9588-2a3e14cfca6f'),
            DeleteAdvancedPurchaseInvoice::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f'],
            null,
        ],
        'advancedPurchase invoice delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->invoice()->delete('3320ef94-a7e8-4d81-9588-2a3e14cfca6f', void: false),
            DeleteAdvancedPurchaseInvoice::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/invoice',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetAdvancedPurchaseInvoice::class => [GetAdvancedPurchaseInvoice::class, ['purchase-1'], Cin7Payloads::load('advanced-purchase/invoice', 'get.response'), AdvancedPurchaseInvoicesData::class, ''],
        PostAdvancedPurchaseInvoice::class => [PostAdvancedPurchaseInvoice::class, [[]], Cin7Payloads::load('advanced-purchase/invoice', 'post.response'), AdvancedPurchaseInvoicesData::class, ''],
        DeleteAdvancedPurchaseInvoice::class => [DeleteAdvancedPurchaseInvoice::class, ['task-1'], Cin7Payloads::load('advanced-purchase/invoice', 'delete.response'), AdvancedPurchaseInvoicesData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePartialInvoicePostData::class => [AdvancedPurchasePartialInvoicePostData::class, Cin7Payloads::load('advanced-purchase/invoice', 'post.request')],
    ],
    'missing' => [
        'advanced purchase invoice envelope without PurchaseID' => [AdvancedPurchaseInvoicesData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'get.response'), 'PurchaseID')],
        'advanced purchase invoice envelope without Invoices' => [AdvancedPurchaseInvoicesData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'get.response'), 'Invoices')],
        'advanced purchase partial invoice without TaskID' => [AdvancedPurchasePartialInvoiceData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'get.response')['Invoices'][0], 'TaskID')],
        'advanced purchase partial invoice without CombineAdditionalCharges' => [AdvancedPurchasePartialInvoiceData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'get.response')['Invoices'][0], 'CombineAdditionalCharges')],
        'advanced purchase partial invoice without InvoiceDueDate' => [AdvancedPurchasePartialInvoiceData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'get.response')['Invoices'][0], 'InvoiceDueDate')],
        'advanced purchase invoice POST without PurchaseID' => [AdvancedPurchasePartialInvoicePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'post.request'), 'PurchaseID')],
        'advanced purchase invoice POST without TaskID' => [AdvancedPurchasePartialInvoicePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'post.request'), 'TaskID')],
        'advanced purchase invoice POST without InvoiceDueDate' => [AdvancedPurchasePartialInvoicePostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/invoice', 'post.request'), 'InvoiceDueDate')],
    ],
    'required' => [
        AdvancedPurchaseInvoicesData::class => ['PurchaseID', 'Invoices'],
        AdvancedPurchasePartialInvoiceData::class => ['Status', 'Lines', 'TaskID', 'CombineAdditionalCharges', 'InvoiceDate', 'InvoiceDueDate'],
        AdvancedPurchasePartialInvoicePostData::class => ['Status', 'Lines', 'PurchaseID', 'TaskID', 'CombineAdditionalCharges', 'InvoiceDate', 'InvoiceDueDate'],
    ],
];
