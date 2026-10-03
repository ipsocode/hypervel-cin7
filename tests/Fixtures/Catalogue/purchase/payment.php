<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentPostData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentPutData;
use Ipsocode\Cin7\Requests\Purchase\Payment\DeletePurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\GetPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\PostPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\PutPurchasePayment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/payment`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetPurchasePayment::class => [
            GetPurchasePayment::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            Method::GET,
            '/ExternalApi/v2/purchase/payment',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        PostPurchasePayment::class => [
            PostPurchasePayment::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5]],
            Method::POST,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5],
        ],
        PutPurchasePayment::class => [
            PutPurchasePayment::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5]],
            Method::PUT,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5],
        ],
        DeletePurchasePayment::class => [
            DeletePurchasePayment::class,
            ['d3d96860-648f-462a-9e19-eb61e03da136'],
            Method::DELETE,
            '/ExternalApi/v2/purchase/payment',
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136'],
            null,
        ],
        PostPurchasePayment::class . ' with data' => [
            PostPurchasePayment::class,
            [fn (): PurchasePaymentPostData => PurchasePaymentPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Reference' => 'PAY-1', 'Amount' => 1.5, 'DatePaid' => '2017-12-21T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0, 'DateCreated' => '2017-12-05T06:54:54.9289846Z'])],
            Method::POST,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['Type' => 'Payment', 'Reference' => 'PAY-1', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Amount' => 1.5, 'Account' => '718'],
        ],
        PutPurchasePayment::class . ' with data' => [
            PutPurchasePayment::class,
            [fn (): PurchasePaymentPutData => PurchasePaymentPutData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5, 'Account' => '718', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0])],
            Method::PUT,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Amount' => 2.5, 'Account' => '718'],
        ],
    ],
    'resources' => [
        'purchase payment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchasePayment::class,
            Method::GET,
            '/ExternalApi/v2/purchase/payment',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase payment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5]),
            PostPurchasePayment::class,
            Method::POST,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5],
        ],
        'purchase payment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->post(PurchasePaymentPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Type' => 'Payment', 'Amount' => 1.5, 'DatePaid' => '2017-12-21T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0, 'DepositID' => 'a8cbf4d7-9f2c-4b5e-8d1a-3c6e2f7b9d40'])),
            PostPurchasePayment::class,
            Method::POST,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['Type' => 'Payment', 'DepositID' => 'a8cbf4d7-9f2c-4b5e-8d1a-3c6e2f7b9d40', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Amount' => 1.5, 'Account' => '718'],
        ],
        'purchase payment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->put(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5]),
            PutPurchasePayment::class,
            Method::PUT,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5],
        ],
        'purchase payment put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->put(PurchasePaymentPutData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Amount' => 2.5, 'Account' => '718', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Reference' => 'PAY-1'])),
            PutPurchasePayment::class,
            Method::PUT,
            '/ExternalApi/v2/purchase/payment',
            [],
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'Reference' => 'PAY-1', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'DatePaid' => '2017-12-21T00:00:00', 'CurrencyRate' => 1.0, 'Amount' => 2.5, 'Account' => '718'],
        ],
        'purchase payment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->payment()->delete('d3d96860-648f-462a-9e19-eb61e03da136', deleteAllocation: false),
            DeletePurchasePayment::class,
            Method::DELETE,
            '/ExternalApi/v2/purchase/payment',
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', 'DeleteAllocation' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetPurchasePayment::class => [GetPurchasePayment::class, ['task-1'], Cin7Payloads::load('purchase/payment', 'get.response'), PurchasePaymentData::class, ''],
        PostPurchasePayment::class => [PostPurchasePayment::class, [[]], Cin7Payloads::load('purchase/payment', 'post.response'), PurchasePaymentData::class, ''],
        PutPurchasePayment::class => [PutPurchasePayment::class, [[]], Cin7Payloads::load('purchase/payment', 'put.response'), PurchasePaymentData::class, ''],
    ],
    'bodies' => [
        PurchasePaymentPostData::class => [PurchasePaymentPostData::class, Cin7Payloads::load('purchase/payment', 'post.request')],
        // The PUT example also sends the POST-only `Type`, which the PUT body does not take.
        PurchasePaymentPutData::class => [PurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'put.request'), 'Type')],
    ],
    'missing' => [
        'purchase payment POST without Account' => [PurchasePaymentPostData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'post.request'), 'Account')],
        'purchase payment PUT without ID' => [PurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'put.request'), 'ID')],
        'purchase payment PUT without Amount' => [PurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'put.request'), 'Amount')],
        'purchase payment PUT without Account' => [PurchasePaymentPutData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'put.request'), 'Account')],
        'purchase payment without TaskID' => [PurchasePaymentData::class, Arr::except(Cin7Payloads::load('purchase/payment', 'get.response')[0], 'TaskID')],
    ],
    'required' => [
        PurchasePaymentData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'Amount', 'Account', 'Type'],
        PurchasePaymentPostData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'Amount', 'Account', 'Type'],
        PurchasePaymentPutData::class => ['TaskID', 'DatePaid', 'CurrencyRate', 'Amount', 'Account', 'ID'],
    ],
    'omitted' => [
        PostPurchasePayment::class => [
            PostPurchasePayment::class,
            ['ID' => 'd3d96860-648f-462a-9e19-eb61e03da136', ...Cin7Payloads::load('purchase/payment', 'post.request')],
            Arr::except(Cin7Payloads::load('purchase/payment', 'post.request'), 'DateCreated'),
        ],
        PutPurchasePayment::class => [
            PutPurchasePayment::class,
            [...Cin7Payloads::load('purchase/payment', 'put.request'), 'DepositID' => null],
            Arr::except(Cin7Payloads::load('purchase/payment', 'put.request'), ['Type', 'DateCreated']),
        ],
    ],
];
