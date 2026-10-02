<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPriceLineData;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;
use Ipsocode\Cin7\Requests\Product\MarkupPrices\GetProductMarkupPrices;
use Ipsocode\Cin7\Requests\Product\MarkupPrices\PutProductMarkupPrices;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `product/markupprices`; tests/Catalogue.php merges every file's rows by kind.

$productId = '7c8795c2-1a6b-4318-ba72-291f61444906';
$body = ['ProductID' => $productId, 'MarkupPrices' => [
    ['TierNumber' => 1, 'MarkupType' => 'A', 'UsePriceType' => 'A', 'MarkupValue' => 11.0],
    ['TierNumber' => 3, 'MarkupType' => 'D'],
]];

return [
    'requests' => [
        GetProductMarkupPrices::class => [
            GetProductMarkupPrices::class,
            [$productId],
            Method::GET,
            '/ExternalApi/v2/product/markupprices',
            ['ProductID' => $productId],
            null,
        ],
        PutProductMarkupPrices::class => [
            PutProductMarkupPrices::class,
            [['ProductID' => $productId]],
            Method::PUT,
            '/ExternalApi/v2/product/markupprices',
            [],
            ['ProductID' => $productId],
        ],
        PutProductMarkupPrices::class . ' with data' => [
            PutProductMarkupPrices::class,
            [fn (): MarkupPricesData => MarkupPricesData::from($body)],
            Method::PUT,
            '/ExternalApi/v2/product/markupprices',
            [],
            $body,
        ],
    ],
    'resources' => [
        'product markupPrices get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->markupPrices()->get($productId),
            GetProductMarkupPrices::class,
            Method::GET,
            '/ExternalApi/v2/product/markupprices',
            ['ProductID' => $productId],
            null,
        ],
        'product markupPrices put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->markupPrices()->put(['ProductID' => $productId]),
            PutProductMarkupPrices::class,
            Method::PUT,
            '/ExternalApi/v2/product/markupprices',
            [],
            ['ProductID' => $productId],
        ],
        'product markupPrices put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->markupPrices()->put(MarkupPricesData::from($body)),
            PutProductMarkupPrices::class,
            Method::PUT,
            '/ExternalApi/v2/product/markupprices',
            [],
            $body,
        ],
    ],
    'dtos' => [
        GetProductMarkupPrices::class => [GetProductMarkupPrices::class, [$productId], Cin7Payloads::load('product/markupprices', 'get.response'), MarkupPricesData::class, ''],
        PutProductMarkupPrices::class => [PutProductMarkupPrices::class, [[]], Cin7Payloads::load('product/markupprices', 'put.response'), MarkupPricesData::class, ''],
    ],
    'bodies' => [
        MarkupPricesData::class => [MarkupPricesData::class, Cin7Payloads::load('product/markupprices', 'put.request')],
    ],
    'missing' => [
        'markup prices without ProductID' => [MarkupPricesData::class, Arr::except(Cin7Payloads::load('product/markupprices', 'put.request'), 'ProductID')],
        'markup price line without MarkupType' => [MarkupPriceLineData::class, ['TierNumber' => 1]],
        'markup price line without TierNumber' => [MarkupPriceLineData::class, ['MarkupType' => 'D']],
    ],
    'required' => [
        MarkupPricesData::class => ['ProductID', 'MarkupPrices'],
        MarkupPriceLineData::class => ['TierNumber', 'MarkupType'],
    ],
];
