<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPutData;
use Ipsocode\Cin7\Requests\Sale\Payment\DeleteSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\GetSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PutSalePayment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/payment`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        DeleteSalePayment::class => [
            DeleteSalePayment::class,
            ['ee093a0c-d177-9728-1df5-628a61a939e4'],
            Method::DELETE,
            '/ExternalApi/v2/sale/payment',
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4'],
            null,
        ],
        GetSalePayment::class => [
            GetSalePayment::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4'],
            Method::GET,
            '/ExternalApi/v2/sale/payment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        PostSalePayment::class => [
            PostSalePayment::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5]],
            Method::POST,
            '/ExternalApi/v2/sale/payment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5],
        ],
        PutSalePayment::class => [
            PutSalePayment::class,
            [['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5]],
            Method::PUT,
            '/ExternalApi/v2/sale/payment',
            [],
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
        ],
        PostSalePayment::class . ' with data' => [
            PostSalePayment::class,
            [fn (): SalePaymentPostData => SalePaymentPostData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0])],
            Method::POST,
            '/ExternalApi/v2/sale/payment',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0],
        ],
        PutSalePayment::class . ' with data' => [
            PutSalePayment::class,
            [fn (): SalePaymentPutData => SalePaymentPutData::from(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5])],
            Method::PUT,
            '/ExternalApi/v2/sale/payment',
            [],
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
        ],
    ],
    'resources' => [
        'sale payment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
            GetSalePayment::class,
            Method::GET,
            '/ExternalApi/v2/sale/payment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        'sale payment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5]),
            PostSalePayment::class,
            Method::POST,
            '/ExternalApi/v2/sale/payment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Amount' => 10.5],
        ],
        'sale payment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->post(SalePaymentPostData::from(['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0])),
            PostSalePayment::class,
            Method::POST,
            '/ExternalApi/v2/sale/payment',
            [],
            ['TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88', 'Type' => 'Payment', 'Amount' => 10.5, 'DatePaid' => '2017-11-30T00:00:00', 'Account' => '718', 'CurrencyRate' => 1.0],
        ],
        'sale payment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->put(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5]),
            PutSalePayment::class,
            Method::PUT,
            '/ExternalApi/v2/sale/payment',
            [],
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
        ],
        'sale payment put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->put(SalePaymentPutData::from(['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5])),
            PutSalePayment::class,
            Method::PUT,
            '/ExternalApi/v2/sale/payment',
            [],
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4', 'Amount' => 12.5],
        ],
        'sale payment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->payment()->delete('ee093a0c-d177-9728-1df5-628a61a939e4'),
            DeleteSalePayment::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/payment',
            ['ID' => 'ee093a0c-d177-9728-1df5-628a61a939e4'],
            null,
        ],
    ],
    'dtos' => [
        GetSalePayment::class => [GetSalePayment::class, ['sale-1'], Cin7Payloads::salePayments(), SalePaymentLinePartialData::class, ''],
        PostSalePayment::class => [PostSalePayment::class, [[]], Cin7Payloads::salePayment(), SalePaymentLinePartialData::class, ''],
        PutSalePayment::class => [PutSalePayment::class, [[]], Cin7Payloads::salePayment(), SalePaymentLinePartialData::class, ''],
    ],
    'bodies' => [
        SalePaymentPostData::class => [SalePaymentPostData::class, Cin7Payloads::salePaymentPost()],
        SalePaymentPutData::class => [SalePaymentPutData::class, Cin7Payloads::salePaymentPut()],
    ],
    'missing' => [
        'payment POST without Type' => [SalePaymentPostData::class, Arr::except(Cin7Payloads::salePaymentPost(), 'Type')],
        'payment PUT without ID' => [SalePaymentPutData::class, Arr::except(Cin7Payloads::salePaymentPut(), 'ID')],
        'payment without TaskID' => [SalePaymentLinePartialData::class, Arr::except(Cin7Payloads::salePayment(), 'TaskID')],
    ],
    'required' => [
        SalePaymentLinePartialData::class => ['ID', 'TaskID', 'Type', 'Amount', 'DatePaid', 'Account', 'CurrencyRate'],
        SalePaymentPostData::class => ['TaskID', 'Type', 'Amount', 'DatePaid', 'Account', 'CurrencyRate'],
        SalePaymentPutData::class => ['ID'],
    ],
    'omitted' => [
        PostSalePayment::class => [
            PostSalePayment::class,
            ['ID' => 'p', 'TaskID' => 't', 'Type' => 'Payment', 'CreditID' => 'c', 'Amount' => 1],
            ['TaskID' => 't', 'Type' => 'Payment', 'Amount' => 1],
        ],
        PutSalePayment::class => [
            PutSalePayment::class,
            ['ID' => 'p', 'TaskID' => 't', 'Type' => 'Payment', 'CreditID' => 'c', 'Amount' => 1],
            ['ID' => 'p', 'CreditID' => 'c', 'Amount' => 1],
        ],
    ],
];
