<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePartialData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\Sale\Invoice\DeleteSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\GetSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PutSaleInvoice;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/invoice`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        DeleteSaleInvoice::class => [
            DeleteSaleInvoice::class,
            ['b039f19e-66f8-4309-a4b1-abf928303c88', ['Void' => true]],
            Method::DELETE,
            '/ExternalApi/v2/sale/invoice',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
        GetSaleInvoice::class => [
            GetSaleInvoice::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4', ['CombineAdditionalCharges' => true]],
            Method::GET,
            '/ExternalApi/v2/sale/invoice',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostSaleInvoice::class => [
            PostSaleInvoice::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000']],
            Method::POST,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000'],
        ],
        PutSaleInvoice::class => [
            PutSaleInvoice::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88']],
            Method::PUT,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
        ],
        PostSaleInvoice::class . ' with data' => [
            PostSaleInvoice::class,
            [fn (): SaleInvoicePostData => SaleInvoicePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Memo' => 'Rush', 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00'])],
            Method::POST,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00', 'Memo' => 'Rush', 'TaskID' => '00000000-0000-0000-0000-000000000000'],
        ],
        PutSaleInvoice::class . ' with data' => [
            PutSaleInvoice::class,
            [fn (): SaleInvoicePutData => SaleInvoicePutData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Lines' => []])],
            Method::PUT,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Lines' => [], 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
        ],
    ],
    'resources' => [
        'sale invoice get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
            GetSaleInvoice::class,
            Method::GET,
            '/ExternalApi/v2/sale/invoice',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        'sale invoice post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
            PostSaleInvoice::class,
            Method::POST,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale invoice post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->post(SaleInvoicePostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => '00000000-0000-0000-0000-000000000000', 'CombineAdditionalCharges' => false, 'Memo' => 'Rush', 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00'])),
            PostSaleInvoice::class,
            Method::POST,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Status' => 'DRAFT', 'InvoiceDate' => '2017-11-22T00:00:00', 'InvoiceDueDate' => '2017-12-22T00:00:00', 'Memo' => 'Rush', 'TaskID' => '00000000-0000-0000-0000-000000000000'],
        ],
        'sale invoice put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->put(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88']),
            PutSaleInvoice::class,
            Method::PUT,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
        ],
        'sale invoice put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->put(SaleInvoicePutData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'])),
            PutSaleInvoice::class,
            Method::PUT,
            '/ExternalApi/v2/sale/invoice',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88'],
        ],
        'sale invoice delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->delete('b039f19e-66f8-4309-a4b1-abf928303c88'),
            DeleteSaleInvoice::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/invoice',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'false'],
            null,
        ],
        'sale invoice delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->invoice()->delete('b039f19e-66f8-4309-a4b1-abf928303c88', void: true),
            DeleteSaleInvoice::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/invoice',
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Void' => 'true'],
            null,
        ],
    ],
    'dtos' => [
        GetSaleInvoice::class => [GetSaleInvoice::class, ['sale-1'], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
        PostSaleInvoice::class => [PostSaleInvoice::class, [[]], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
        PutSaleInvoice::class => [PutSaleInvoice::class, [[]], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
        DeleteSaleInvoice::class => [DeleteSaleInvoice::class, ['task-1'], Cin7Payloads::saleInvoices(), SaleInvoicesData::class, ''],
    ],
    'bodies' => [
        SaleInvoicePostData::class => [SaleInvoicePostData::class, Cin7Payloads::saleInvoicePost()],
        SaleInvoicePutData::class => [SaleInvoicePutData::class, Cin7Payloads::saleInvoicePut()],
    ],
    'missing' => [
        'invoice POST without Status' => [SaleInvoicePostData::class, Arr::except(Cin7Payloads::saleInvoicePost(), 'Status')],
        'invoice PUT without TaskID' => [SaleInvoicePutData::class, Arr::except(Cin7Payloads::saleInvoicePut(), 'TaskID')],
        'invoice without InvoiceDate' => [SaleInvoicePartialData::class, Arr::except(Cin7Payloads::saleInvoicePartial(), 'InvoiceDate')],
    ],
    'required' => [
        SaleInvoicePartialData::class => ['TaskID', 'CombineAdditionalCharges', 'Status', 'InvoiceDate', 'InvoiceDueDate'],
        SaleInvoicePostData::class => ['SaleID', 'TaskID', 'CombineAdditionalCharges', 'Status', 'InvoiceDate', 'InvoiceDueDate'],
        SaleInvoicePutData::class => ['SaleID', 'TaskID'],
    ],
];
