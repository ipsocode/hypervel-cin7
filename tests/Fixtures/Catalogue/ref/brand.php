<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Brand\BrandData;
use Ipsocode\Cin7\Data\Ref\Brand\BrandPostData;
use Ipsocode\Cin7\Data\Ref\Brand\BrandPutData;
use Ipsocode\Cin7\Requests\Ref\Brand\DeleteBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\GetBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\PostBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\PutBrand;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/brand`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetBrand::class => [
            GetBrand::class,
            ['name' => 'Test'],
            Method::GET,
            '/ExternalApi/v2/ref/brand',
            ['Name' => 'Test', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostBrand::class => [
            PostBrand::class,
            [['Name' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/ref/brand',
            [],
            ['Name' => 'Test'],
        ],
        PutBrand::class => [
            PutBrand::class,
            [['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']],
            Method::PUT,
            '/ExternalApi/v2/ref/brand',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        DeleteBrand::class => [
            DeleteBrand::class,
            ['ca585936-d523-4c40-980b-fbd0199d61fc'],
            Method::DELETE,
            '/ExternalApi/v2/ref/brand',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
        PostBrand::class . ' with data' => [
            PostBrand::class,
            [fn (): BrandPostData => BrandPostData::from(['Name' => 'Test'])],
            Method::POST,
            '/ExternalApi/v2/ref/brand',
            [],
            ['Name' => 'Test'],
        ],
        PutBrand::class . ' with data' => [
            PutBrand::class,
            [fn (): BrandPutData => BrandPutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])],
            Method::PUT,
            '/ExternalApi/v2/ref/brand',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
    ],
    'resources' => [
        'ref brand get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->get(),
            GetBrand::class,
            Method::GET,
            '/ExternalApi/v2/ref/brand',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref brand paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->paginate(name: 'A')->current(),
            GetBrand::class,
            Method::GET,
            '/ExternalApi/v2/ref/brand',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref brand post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->post(['Name' => 'Test']),
            PostBrand::class,
            Method::POST,
            '/ExternalApi/v2/ref/brand',
            [],
            ['Name' => 'Test'],
        ],
        'ref brand post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->post(BrandPostData::from(['Name' => 'Test'])),
            PostBrand::class,
            Method::POST,
            '/ExternalApi/v2/ref/brand',
            [],
            ['Name' => 'Test'],
        ],
        'ref brand put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->put(['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']),
            PutBrand::class,
            Method::PUT,
            '/ExternalApi/v2/ref/brand',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref brand put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->put(BrandPutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])),
            PutBrand::class,
            Method::PUT,
            '/ExternalApi/v2/ref/brand',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref brand delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->brand()->delete('ca585936-d523-4c40-980b-fbd0199d61fc'),
            DeleteBrand::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/brand',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
    ],
    'dtos' => [
        GetBrand::class => [GetBrand::class, [], Cin7Payloads::load('ref/brand', 'get.response'), BrandData::class, 'BrandList'],
        PostBrand::class => [PostBrand::class, [[]], Cin7Payloads::load('ref/brand', 'post.response'), BrandData::class, ''],
        PutBrand::class => [PutBrand::class, [[]], Cin7Payloads::load('ref/brand', 'put.response'), BrandData::class, ''],
    ],
    'bodies' => [
        BrandPostData::class => [BrandPostData::class, Cin7Payloads::load('ref/brand', 'post.request')],
        BrandPutData::class => [BrandPutData::class, Cin7Payloads::load('ref/brand', 'put.request')],
    ],
    'missing' => [
        'brand POST without Name' => [BrandPostData::class, []],
        'brand PUT without ID' => [BrandPutData::class, Arr::except(Cin7Payloads::load('ref/brand', 'put.request'), 'ID')],
        'brand without Name' => [BrandData::class, Arr::except(Cin7Payloads::load('ref/brand', 'post.response'), 'Name')],
    ],
    'required' => [
        BrandData::class => ['Name'],
        BrandPostData::class => ['Name'],
        BrandPutData::class => ['Name', 'ID'],
    ],
];
