<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\PurchaseAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderLineData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderPostData;
use Ipsocode\Cin7\Requests\Purchase\Order\GetPurchaseOrder;
use Ipsocode\Cin7\Requests\Purchase\Order\PostPurchaseOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/order`; tests/Catalogue.php merges every file's rows by kind.

// The fields every order POST requires.
$fields = ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Status' => 'DRAFT', 'Lines' => []];

// One line and one additional charge, with every field the tables require.
$line = ['ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 2, 'Price' => 2, 'Tax' => 0, 'TaxRule' => 'Sales Tax on Imports', 'Total' => 4, 'SupplierSKU' => 'BRD-1'];
$charge = ['Description' => 'Freight', 'Quantity' => 1, 'Price' => 3, 'Tax' => 0, 'TaxRule' => 'Sales Tax on Imports', 'Total' => 3, 'Reference' => 'FRT-1'];

return [
    'requests' => [
        GetPurchaseOrder::class => [
            GetPurchaseOrder::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/purchase/order',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostPurchaseOrder::class => [
            PostPurchaseOrder::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/purchase/order',
            [],
            $fields,
        ],
        PostPurchaseOrder::class . ' with data' => [
            PostPurchaseOrder::class,
            [fn (): PurchaseOrderPostData => PurchaseOrderPostData::from([...$fields, 'AdditionalCharges' => null])],
            Method::POST,
            '/ExternalApi/v2/purchase/order',
            [],
            ['Status' => 'DRAFT', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Lines' => []],
        ],
    ],
    'resources' => [
        'purchase order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->order()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136', combineAdditionalCharges: false),
            GetPurchaseOrder::class,
            Method::GET,
            '/ExternalApi/v2/purchase/order',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'CombineAdditionalCharges' => 'false'],
            null,
        ],
        'purchase order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->order()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136']),
            PostPurchaseOrder::class,
            Method::POST,
            '/ExternalApi/v2/purchase/order',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
        ],
        'purchase order post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->order()->post(PurchaseOrderPostData::from([...$fields, 'Status' => 'AUTHORISED', 'Lines' => [$line], 'AdditionalCharges' => [$charge]])),
            PostPurchaseOrder::class,
            Method::POST,
            '/ExternalApi/v2/purchase/order',
            [],
            [
                'Status' => 'AUTHORISED',
                'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136',
                'CombineAdditionalCharges' => false,
                'Memo' => '',
                'AdditionalCharges' => [['Reference' => 'FRT-1', 'Total' => 3.0, 'Description' => 'Freight', 'Quantity' => 1.0, 'Price' => 3.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
                'Lines' => [['SupplierSKU' => 'BRD-1', 'Total' => 4.0, 'ProductID' => 'c08b3876-89cc-46c4-af52-b77f058fdf81', 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Quantity' => 2.0, 'Price' => 2.0, 'Tax' => 0.0, 'TaxRule' => 'Sales Tax on Imports']],
            ],
        ],
    ],
    'dtos' => [
        GetPurchaseOrder::class => [GetPurchaseOrder::class, ['task-1'], Cin7Payloads::load('purchase/order', 'get.response'), PurchaseOrderData::class, ''],
        PostPurchaseOrder::class => [PostPurchaseOrder::class, [[]], Cin7Payloads::load('purchase/order', 'post.response'), PurchaseOrderData::class, ''],
    ],
    'bodies' => [
        PurchaseOrderPostData::class => [PurchaseOrderPostData::class, Cin7Payloads::load('purchase/order', 'post.request')],
    ],
    'missing' => [
        'purchase order POST without TaskID' => [PurchaseOrderPostData::class, Arr::except(Cin7Payloads::load('purchase/order', 'post.request'), 'TaskID')],
        'purchase order POST without CombineAdditionalCharges' => [PurchaseOrderPostData::class, Arr::except(Cin7Payloads::load('purchase/order', 'post.request'), 'CombineAdditionalCharges')],
        'purchase order POST without Memo' => [PurchaseOrderPostData::class, Arr::except(Cin7Payloads::load('purchase/order', 'post.request'), 'Memo')],
        'purchase order without Total' => [PurchaseOrderData::class, Arr::except(Cin7Payloads::load('purchase/order', 'get.response'), 'Total')],
        'purchase order line without Total' => [PurchaseOrderLineData::class, Arr::except($line, 'Total')],
        'purchase additional charge without Total' => [PurchaseAdditionalChargeData::class, Arr::except($charge, 'Total')],
    ],
    'required' => [
        PurchaseOrderData::class => ['Status', 'Lines', 'TotalBeforeTax', 'Tax', 'Total'],
        PurchaseOrderPostData::class => ['Status', 'Lines', 'TaskID', 'CombineAdditionalCharges', 'Memo'],
        PurchaseOrderLineData::class => ['ProductID', 'SKU', 'Name', 'Quantity', 'Price', 'Tax', 'TaxRule', 'Total'],
        PurchaseAdditionalChargeData::class => ['Description', 'Quantity', 'Price', 'Tax', 'TaxRule', 'Total'],
    ],
];
