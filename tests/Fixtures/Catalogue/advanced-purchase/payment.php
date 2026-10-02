<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentPutData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\GetAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\PostAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\PutAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\DeletePurchasePayment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/payment`; tests/Catalogue.php merges every file's rows by kind.
// Its DELETE is documented on `purchase/payment`: the resource sends DeletePurchasePayment, whose
// request row is in purchase/payment.php.

return [
    'requests' => [
        GetAdvancedPurchasePayment::class => [
            GetAdvancedPurchasePayment::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'PO-00094', 'INV-00094', 'CR-00094'],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/payment',
            ['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'OrderNumber' => 'PO-00094', 'InvoiceNumber' => 'INV-00094', 'CreditNoteNumber' => 'CR-00094'],
            null,
        ],
        PostAdvancedPurchasePayment::class => [
            PostAdvancedPurchasePayment::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5]],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5],
        ],
        PutAdvancedPurchasePayment::class => [
            PutAdvancedPurchasePayment::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5]],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5],
        ],
        PostAdvancedPurchasePayment::class . ' with data' => [
            PostAdvancedPurchasePayment::class,
            [fn (): AdvancedPurchasePaymentPostData => AdvancedPurchasePaymentPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Reference' => 'PAY-1', 'Amount' => 1.5, 'DatePaid' => '2017-12-21T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0, 'DateCreated' => '2017-12-05T06:54:54.9289846Z'])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['Type' => 'Payment', 'Amount' => 1.5, 'Account' => '718', 'Reference' => 'PAY-1', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0],
        ],
        PutAdvancedPurchasePayment::class . ' with data' => [
            PutAdvancedPurchasePayment::class,
            [fn (): AdvancedPurchasePaymentPutData => AdvancedPurchasePaymentPutData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5, 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0])],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5, 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0],
        ],
    ],
    'resources' => [
        'advancedPurchase payment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetAdvancedPurchasePayment::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/payment',
            ['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'advancedPurchase payment get by numbers' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->get(orderNumber: 'PO-00094', invoiceNumber: 'INV-00094', creditNoteNumber: 'CR-00094'),
            GetAdvancedPurchasePayment::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/payment',
            ['OrderNumber' => 'PO-00094', 'InvoiceNumber' => 'INV-00094', 'CreditNoteNumber' => 'CR-00094'],
            null,
        ],
        'advancedPurchase payment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5]),
            PostAdvancedPurchasePayment::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5],
        ],
        'advancedPurchase payment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->post(AdvancedPurchasePaymentPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5, 'DatePaid' => '2017-12-21T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0, 'DepositID' => 'a8cbf4d7-9f2c-4b5e-8d1a-3c6e2f7b9d40'])),
            PostAdvancedPurchasePayment::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['Type' => 'Payment', 'Amount' => 1.5, 'Account' => '718', 'DepositID' => 'a8cbf4d7-9f2c-4b5e-8d1a-3c6e2f7b9d40', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0],
        ],
        'advancedPurchase payment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->put(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5]),
            PutAdvancedPurchasePayment::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5],
        ],
        'advancedPurchase payment put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->put(AdvancedPurchasePaymentPutData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Reference' => 'PAY-1'])),
            PutAdvancedPurchasePayment::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/payment',
            [],
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Reference' => 'PAY-1', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0],
        ],
        'advancedPurchase payment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->payment()->delete('d3d96860-648f-462a-9e19-eb61e03da136', deleteAllocation: false),
            DeletePurchasePayment::class,
            Method::DELETE,
            '/ExternalApi/v2/purchase/payment',
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'DeleteAllocation' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetAdvancedPurchasePayment::class => [GetAdvancedPurchasePayment::class, ['02b08cd2-51d2-41e6-ab97-85bcd13e7136'], Cin7Payloads::load('advanced-purchase/payment', 'get.response'), AdvancedPurchasePaymentData::class, ''],
        PostAdvancedPurchasePayment::class => [PostAdvancedPurchasePayment::class, [[]], Cin7Payloads::load('advanced-purchase/payment', 'post.response'), AdvancedPurchasePaymentData::class, ''],
        PutAdvancedPurchasePayment::class => [PutAdvancedPurchasePayment::class, [[]], Cin7Payloads::load('advanced-purchase/payment', 'put.response'), AdvancedPurchasePaymentData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePaymentPostData::class => [AdvancedPurchasePaymentPostData::class, Cin7Payloads::load('advanced-purchase/payment', 'post.request')],
        // The PUT example also sends the POST-only `Type`, which the PUT body does not take.
        AdvancedPurchasePaymentPutData::class => [AdvancedPurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'put.request'), 'Type')],
    ],
    'missing' => [
        'advanced purchase payment POST without Account' => [AdvancedPurchasePaymentPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'post.request'), 'Account')],
        'advanced purchase payment PUT without ID' => [AdvancedPurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'put.request'), 'ID')],
        'advanced purchase payment without TaskID' => [AdvancedPurchasePaymentData::class, Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'get.response')[0], 'TaskID')],
    ],
    'required' => [
        AdvancedPurchasePaymentData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'Type', 'Amount', 'Account'],
        AdvancedPurchasePaymentPostData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'Type', 'Amount', 'Account'],
        AdvancedPurchasePaymentPutData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'ID'],
    ],
    'omitted' => [
        PostAdvancedPurchasePayment::class => [
            PostAdvancedPurchasePayment::class,
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', ...Cin7Payloads::load('advanced-purchase/payment', 'post.request')],
            Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'post.request'), 'DateCreated'),
        ],
        PutAdvancedPurchasePayment::class => [
            PutAdvancedPurchasePayment::class,
            [...Cin7Payloads::load('advanced-purchase/payment', 'put.request'), 'DepositID' => null],
            Arr::except(Cin7Payloads::load('advanced-purchase/payment', 'put.request'), ['Type', 'DateCreated']),
        ],
    ],
];
