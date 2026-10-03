<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteData;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuotePostData;
use Ipsocode\Cin7\Requests\Sale\Quote\GetSaleQuote;
use Ipsocode\Cin7\Requests\Sale\Quote\PostSaleQuote;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/quote`; tests/Catalogue.php merges every file's rows by kind.

// The fields every quote POST requires.
$fields = ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Status' => 'DRAFT', 'Lines' => []];

return [
    'requests' => [
        GetSaleQuote::class => [
            GetSaleQuote::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4', 'combineAdditionalCharges' => true],
            Method::GET,
            '/ExternalApi/v2/sale/quote',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => 'true'],
            null,
        ],
        PostSaleQuote::class => [
            PostSaleQuote::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/sale/quote',
            [],
            $fields,
        ],
        PostSaleQuote::class . ' with data' => [
            PostSaleQuote::class,
            [fn (): SaleQuotePostData => SaleQuotePostData::from([...$fields, 'Prepayments' => null])],
            Method::POST,
            '/ExternalApi/v2/sale/quote',
            [],
            ['Status' => 'DRAFT', 'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Lines' => []],
        ],
    ],
    'resources' => [
        'sale quote get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->quote()->get('916ab4c0-6ccb-4c93-873d-0603859050e4', includeProductInfo: true),
            GetSaleQuote::class,
            Method::GET,
            '/ExternalApi/v2/sale/quote',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
            null,
        ],
        'sale quote post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->quote()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
            PostSaleQuote::class,
            Method::POST,
            '/ExternalApi/v2/sale/quote',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale quote post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->quote()->post(SaleQuotePostData::from([...$fields, 'Status' => 'AUTHORISED'])),
            PostSaleQuote::class,
            Method::POST,
            '/ExternalApi/v2/sale/quote',
            [],
            ['Status' => 'AUTHORISED', 'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CombineAdditionalCharges' => false, 'Memo' => '', 'Lines' => []],
        ],
    ],
    'dtos' => [
        GetSaleQuote::class => [GetSaleQuote::class, ['sale-1'], Cin7Payloads::load('sale/quote', 'get.response'), SaleQuoteData::class, ''],
        PostSaleQuote::class => [PostSaleQuote::class, [[]], Cin7Payloads::load('sale/quote', 'get.response'), SaleQuoteData::class, ''],
    ],
    'bodies' => [
        SaleQuotePostData::class => [SaleQuotePostData::class, Cin7Payloads::load('sale/quote', 'post.request')],
    ],
    'missing' => [
        'quote POST without SaleID' => [SaleQuotePostData::class, Arr::except(Cin7Payloads::load('sale/quote', 'post.request'), 'SaleID')],
        'quote without Total' => [SaleQuoteData::class, Arr::except(Cin7Payloads::load('sale/quote', 'get.response'), 'Total')],
    ],
    'required' => [
        SaleQuotePostData::class => ['Memo', 'Status', 'Lines', 'SaleID', 'CombineAdditionalCharges'],
    ],
];
