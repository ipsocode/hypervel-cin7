<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryData;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryPostData;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryPutData;
use Ipsocode\Cin7\Requests\Ref\Category\DeleteCategory;
use Ipsocode\Cin7\Requests\Ref\Category\GetCategory;
use Ipsocode\Cin7\Requests\Ref\Category\PostCategory;
use Ipsocode\Cin7\Requests\Ref\Category\PutCategory;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/category`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetCategory::class => [
            GetCategory::class,
            ['name' => 'Test'],
            Method::GET,
            '/ExternalApi/v2/ref/category',
            ['Name' => 'Test', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostCategory::class => [
            PostCategory::class,
            [['Name' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/ref/category',
            [],
            ['Name' => 'Test'],
        ],
        PutCategory::class => [
            PutCategory::class,
            [['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']],
            Method::PUT,
            '/ExternalApi/v2/ref/category',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        DeleteCategory::class => [
            DeleteCategory::class,
            ['ca585936-d523-4c40-980b-fbd0199d61fc'],
            Method::DELETE,
            '/ExternalApi/v2/ref/category',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
        PostCategory::class . ' with data' => [
            PostCategory::class,
            [fn (): ProductCategoryPostData => ProductCategoryPostData::from(['Name' => 'Test'])],
            Method::POST,
            '/ExternalApi/v2/ref/category',
            [],
            ['Name' => 'Test'],
        ],
        PutCategory::class . ' with data' => [
            PutCategory::class,
            [fn (): ProductCategoryPutData => ProductCategoryPutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])],
            Method::PUT,
            '/ExternalApi/v2/ref/category',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
    ],
    'resources' => [
        'ref category get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->get(),
            GetCategory::class,
            Method::GET,
            '/ExternalApi/v2/ref/category',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref category paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->paginate(name: 'A')->current(),
            GetCategory::class,
            Method::GET,
            '/ExternalApi/v2/ref/category',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref category post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->post(['Name' => 'Test']),
            PostCategory::class,
            Method::POST,
            '/ExternalApi/v2/ref/category',
            [],
            ['Name' => 'Test'],
        ],
        'ref category post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->post(ProductCategoryPostData::from(['Name' => 'Test'])),
            PostCategory::class,
            Method::POST,
            '/ExternalApi/v2/ref/category',
            [],
            ['Name' => 'Test'],
        ],
        'ref category put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->put(['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']),
            PutCategory::class,
            Method::PUT,
            '/ExternalApi/v2/ref/category',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref category put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->put(ProductCategoryPutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])),
            PutCategory::class,
            Method::PUT,
            '/ExternalApi/v2/ref/category',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref category delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->category()->delete('ca585936-d523-4c40-980b-fbd0199d61fc'),
            DeleteCategory::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/category',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
    ],
    'dtos' => [
        GetCategory::class => [GetCategory::class, [], Cin7Payloads::load('ref/category', 'get.response'), ProductCategoryData::class, 'CategoryList'],
        PostCategory::class => [PostCategory::class, [[]], Cin7Payloads::load('ref/category', 'post.response'), ProductCategoryData::class, ''],
        PutCategory::class => [PutCategory::class, [[]], Cin7Payloads::load('ref/category', 'put.response'), ProductCategoryData::class, ''],
    ],
    'bodies' => [
        ProductCategoryPostData::class => [ProductCategoryPostData::class, Cin7Payloads::load('ref/category', 'post.request')],
        ProductCategoryPutData::class => [ProductCategoryPutData::class, Cin7Payloads::load('ref/category', 'put.request')],
    ],
    'missing' => [
        'product category POST without Name' => [ProductCategoryPostData::class, []],
        'product category PUT without ID' => [ProductCategoryPutData::class, Arr::except(Cin7Payloads::load('ref/category', 'put.request'), 'ID')],
        'product category without Name' => [ProductCategoryData::class, Arr::except(Cin7Payloads::load('ref/category', 'post.response'), 'Name')],
    ],
    'required' => [
        ProductCategoryData::class => ['Name'],
        ProductCategoryPostData::class => ['Name'],
        ProductCategoryPutData::class => ['Name', 'ID'],
    ],
];
