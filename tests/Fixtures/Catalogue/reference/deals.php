<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealDiscountData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPostData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPutData;
use Ipsocode\Cin7\Requests\Reference\Deals\GetDeals;
use Ipsocode\Cin7\Requests\Reference\Deals\PostDeals;
use Ipsocode\Cin7\Requests\Reference\Deals\PutDeals;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `reference/deals`; tests/Catalogue.php merges every file's rows by kind.

$dealId = '8b89117b-e73c-4ccd-9bbc-8e7c95bfaa6c';
$discount = ['DiscountType' => 'FreeShipping', 'IsOrderLevel' => true, 'DiscountName' => 'Free shiping'];
$uri = '/ExternalApi/v2/reference/deals';

return [
    'requests' => [
        GetDeals::class => [
            GetDeals::class,
            ['id' => $dealId, 'search' => 'Deal'],
            Method::GET,
            $uri,
            ['ID' => $dealId, 'Search' => 'Deal', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostDeals::class => [
            PostDeals::class,
            [['Name' => 'Product Deal 1']],
            Method::POST,
            $uri,
            [],
            ['Name' => 'Product Deal 1'],
        ],
        PutDeals::class => [
            PutDeals::class,
            [['ID' => $dealId, 'Name' => 'Product Deal 1a']],
            Method::PUT,
            $uri,
            [],
            ['ID' => $dealId, 'Name' => 'Product Deal 1a'],
        ],
        PostDeals::class . ' with data' => [
            PostDeals::class,
            [fn (): ProductDealPostData => ProductDealPostData::from(['Name' => 'Product Deal 1', 'CustomersGroup' => 'POS', 'DealDiscounts' => [$discount]])],
            Method::POST,
            $uri,
            [],
            ['CustomersGroup' => 'POS', 'DealDiscounts' => [['DiscountType' => 'FreeShipping', 'IsOrderLevel' => true, 'DiscountName' => 'Free shiping']], 'Name' => 'Product Deal 1'],
        ],
        PutDeals::class . ' with data' => [
            PutDeals::class,
            [fn (): ProductDealPutData => ProductDealPutData::from(['ID' => $dealId, 'Name' => 'Product Deal 1a', 'IsActive' => true])],
            Method::PUT,
            $uri,
            [],
            ['ID' => $dealId, 'IsActive' => true, 'Name' => 'Product Deal 1a'],
        ],
    ],
    'resources' => [
        'reference deals get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->get(search: 'Deal'),
            GetDeals::class,
            Method::GET,
            $uri,
            ['Search' => 'Deal', 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference deals paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->paginate(id: $dealId)->current(),
            GetDeals::class,
            Method::GET,
            $uri,
            ['ID' => $dealId, 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference deals post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->post(['Name' => 'Product Deal 1']),
            PostDeals::class,
            Method::POST,
            $uri,
            [],
            ['Name' => 'Product Deal 1'],
        ],
        'reference deals post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->post(ProductDealPostData::from(['Name' => 'Product Deal 1'])),
            PostDeals::class,
            Method::POST,
            $uri,
            [],
            ['Name' => 'Product Deal 1'],
        ],
        'reference deals put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->put(['ID' => $dealId, 'Name' => 'Product Deal 1a']),
            PutDeals::class,
            Method::PUT,
            $uri,
            [],
            ['ID' => $dealId, 'Name' => 'Product Deal 1a'],
        ],
        'reference deals put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->deals()->put(ProductDealPutData::from(['ID' => $dealId, 'Name' => 'Product Deal 1a'])),
            PutDeals::class,
            Method::PUT,
            $uri,
            [],
            ['ID' => $dealId, 'Name' => 'Product Deal 1a'],
        ],
    ],
    'dtos' => [
        GetDeals::class => [GetDeals::class, [], Cin7Payloads::load('reference/deals', 'get.response'), ProductDealData::class, 'Deals'],
        PostDeals::class => [PostDeals::class, [[]], Cin7Payloads::load('reference/deals', 'post.response'), ProductDealData::class, 'Deals.0'],
        PutDeals::class => [PutDeals::class, [[]], Cin7Payloads::load('reference/deals', 'put.response'), ProductDealData::class, 'Deals.0'],
    ],
    'bodies' => [
        ProductDealPostData::class => [ProductDealPostData::class, Cin7Payloads::load('reference/deals', 'post.request')],
        ProductDealPutData::class => [ProductDealPutData::class, Cin7Payloads::load('reference/deals', 'put.request')],
        'deal with customers and tags' => [ProductDealPostData::class, [
            'Name' => 'Product Deal 1',
            'DealCustomers' => [['ID' => $dealId, 'CustomerID' => $dealId, 'CustomerName' => 'Customer 1']],
            'DealCustomerTags' => [['ID' => $dealId, 'TagName' => 'VIP']],
        ]],
    ],
    'missing' => [
        'deal POST without Name' => [ProductDealPostData::class, Arr::except(Cin7Payloads::load('reference/deals', 'post.request'), 'Name')],
        'deal PUT without ID' => [ProductDealPutData::class, Arr::except(Cin7Payloads::load('reference/deals', 'put.request'), 'ID')],
        'deal without IsActive' => [ProductDealData::class, Arr::except(Cin7Payloads::load('reference/deals', 'get.response')['Deals'][0], 'IsActive')],
        'deal discount without IsOrderLevel' => [ProductDealDiscountData::class, Arr::except(Cin7Payloads::load('reference/deals', 'post.request')['DealDiscounts'][0], 'IsOrderLevel')],
    ],
    'required' => [
        ProductDealData::class => ['ID', 'Name', 'IsActive', 'AllowCoupons', 'SingleCouponCodeUsage'],
        ProductDealPostData::class => ['Name'],
        ProductDealPutData::class => ['Name', 'ID'],
        ProductDealDiscountData::class => ['DiscountType', 'IsOrderLevel'],
    ],
];
