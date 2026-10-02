<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\AddressData;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNoteData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentPickPackData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentPickPackLineData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipLineData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoiceData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoiceLineData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalLineData;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderLineData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteLineData;
use Ipsocode\Cin7\Data\Sale\SaleAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Data\Sale\SalePostData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Data\Sale\SaleShippingAddressData;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        DeleteSale::class => [
            DeleteSale::class,
            ['0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'true'],
            null,
        ],
        GetSale::class => [
            GetSale::class,
            ['0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'includeTransactions' => true],
            Method::GET,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'IncludeTransactions' => 'true'],
            null,
        ],
        PostSale::class => [
            PostSale::class,
            [['Customer' => 'ACME']],
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME'],
        ],
        PutSale::class => [
            PutSale::class,
            [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush']],
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
        ],
        PostSale::class . ' with data' => [
            PostSale::class,
            [fn (): SalePostData => SalePostData::from(['Customer' => 'ACME', 'SkipQuote' => false, 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])],
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME', 'SkipQuote' => false, 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0],
        ],
        PutSale::class . ' with data' => [
            PutSale::class,
            [fn (): SalePutData => SalePutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'ShippingAddress' => ['Line1' => '1 High St', 'Country' => 'UK'], 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])],
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'ShippingAddress' => ['Line1' => '1 High St', 'Country' => 'UK'], 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0],
        ],
    ],
    'resources' => [
        'sale get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->get('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', combineAdditionalCharges: true),
            GetSale::class,
            Method::GET,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        'sale post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(['Customer' => 'ACME']),
            PostSale::class,
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME'],
        ],
        'sale put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush']),
            PutSale::class,
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
        ],
        'sale delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'),
            DeleteSale::class,
            Method::DELETE,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'],
            null,
        ],
        'sale delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', void: true),
            DeleteSale::class,
            Method::DELETE,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'true'],
            null,
        ],
        'sale post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(SalePostData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])),
            PostSale::class,
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0],
        ],
        'sale put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(SalePutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Note' => 'Rush', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])),
            PutSale::class,
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Note' => 'Rush', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0],
        ],
    ],
    'dtos' => [
        GetSale::class => [GetSale::class, ['guid-1'], Cin7Payloads::sale(), SaleData::class, ''],
        PostSale::class => [PostSale::class, [[]], Cin7Payloads::sale(), SaleData::class, ''],
        PutSale::class => [PutSale::class, [[]], Cin7Payloads::sale(), SaleData::class, ''],
        DeleteSale::class => [DeleteSale::class, ['guid-1'], Cin7Payloads::sale(), SaleData::class, ''],
    ],
    'required' => [
        SaleQuoteLineData::class => ['ProductID', 'SKU', 'Name', 'Quantity', 'Price', 'Tax', 'TaxRule', 'AverageCost', 'Comment'],
        SaleOrderLineData::class => ['ProductID', 'SKU', 'Name', 'Quantity', 'Price', 'Tax', 'TaxRule'],
        SaleInvoiceLineData::class => ['ProductID', 'SKU', 'Name', 'Quantity', 'Price', 'Tax', 'TaxRule', 'Total'],
        SaleAdditionalChargeData::class => ['Description', 'Quantity', 'Price', 'Tax', 'TaxRule'],
        SaleInvoiceAdditionalChargeData::class => ['Description', 'Quantity', 'Price', 'Tax', 'TaxRule'],
        AddressData::class => ['Line1', 'Country'],
        SaleShippingAddressData::class => ['Line1', 'Country'],
        SaleData::class => ['Location', 'CurrencyRate', 'CombinedPickingStatus', 'CombinedPackingStatus', 'CombinedShippingStatus'],
        SalePostData::class => ['Location', 'CurrencyRate'],
        SalePutData::class => ['Location', 'CurrencyRate', 'ID'],
        SaleInvoiceData::class => ['TaskID', 'Status', 'InvoiceDate', 'InvoiceDueDate'],
        SaleCreditNoteData::class => ['TaskID', 'Status', 'CreditNoteDate', 'CreditNoteInvoiceNumber'],
        SaleQuoteData::class => ['Memo', 'Status', 'Lines', 'TotalBeforeTax', 'Tax', 'Total'],
        SaleFulfilmentData::class => ['TaskID', 'FulfillmentNumber', 'FulFilmentStatus'],
        SaleFulfilmentPickPackData::class => ['Status'],
        SaleFulfilmentPickPackLineData::class => ['SKU', 'Quantity'],
        SaleFulfilmentShipData::class => ['Status'],
        SaleFulfilmentShipLineData::class => ['ShipmentDate', 'Boxes'],
        SaleManualJournalData::class => ['Status'],
        SaleManualJournalLineData::class => ['Amount', 'Date', 'Debit', 'Credit'],
    ],
];
