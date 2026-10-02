<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasureData;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasurePostData;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasurePutData;
use Ipsocode\Cin7\Requests\Ref\Unit\DeleteUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\GetUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\PostUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\PutUnit;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/unit`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetUnit::class => [
            GetUnit::class,
            ['name' => 'Test'],
            Method::GET,
            '/ExternalApi/v2/ref/unit',
            ['Name' => 'Test', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostUnit::class => [
            PostUnit::class,
            [['Name' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/ref/unit',
            [],
            ['Name' => 'Test'],
        ],
        PutUnit::class => [
            PutUnit::class,
            [['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']],
            Method::PUT,
            '/ExternalApi/v2/ref/unit',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        DeleteUnit::class => [
            DeleteUnit::class,
            ['ca585936-d523-4c40-980b-fbd0199d61fc'],
            Method::DELETE,
            '/ExternalApi/v2/ref/unit',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
        PostUnit::class . ' with data' => [
            PostUnit::class,
            [fn (): UnitOfMeasurePostData => UnitOfMeasurePostData::from(['Name' => 'Test'])],
            Method::POST,
            '/ExternalApi/v2/ref/unit',
            [],
            ['Name' => 'Test'],
        ],
        PutUnit::class . ' with data' => [
            PutUnit::class,
            [fn (): UnitOfMeasurePutData => UnitOfMeasurePutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])],
            Method::PUT,
            '/ExternalApi/v2/ref/unit',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
    ],
    'resources' => [
        'ref unit get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->get(),
            GetUnit::class,
            Method::GET,
            '/ExternalApi/v2/ref/unit',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref unit paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->paginate(name: 'A')->current(),
            GetUnit::class,
            Method::GET,
            '/ExternalApi/v2/ref/unit',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref unit post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->post(['Name' => 'Test']),
            PostUnit::class,
            Method::POST,
            '/ExternalApi/v2/ref/unit',
            [],
            ['Name' => 'Test'],
        ],
        'ref unit post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->post(UnitOfMeasurePostData::from(['Name' => 'Test'])),
            PostUnit::class,
            Method::POST,
            '/ExternalApi/v2/ref/unit',
            [],
            ['Name' => 'Test'],
        ],
        'ref unit put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->put(['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1']),
            PutUnit::class,
            Method::PUT,
            '/ExternalApi/v2/ref/unit',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref unit put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->put(UnitOfMeasurePutData::from(['Name' => 'Test1', 'ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'])),
            PutUnit::class,
            Method::PUT,
            '/ExternalApi/v2/ref/unit',
            [],
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc', 'Name' => 'Test1'],
        ],
        'ref unit delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->unit()->delete('ca585936-d523-4c40-980b-fbd0199d61fc'),
            DeleteUnit::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/unit',
            ['ID' => 'ca585936-d523-4c40-980b-fbd0199d61fc'],
            null,
        ],
    ],
    'dtos' => [
        GetUnit::class => [GetUnit::class, [], Cin7Payloads::load('ref/unit', 'get.response'), UnitOfMeasureData::class, 'UnitList'],
        PostUnit::class => [PostUnit::class, [[]], Cin7Payloads::load('ref/unit', 'post.response'), UnitOfMeasureData::class, ''],
        PutUnit::class => [PutUnit::class, [[]], Cin7Payloads::load('ref/unit', 'put.response'), UnitOfMeasureData::class, ''],
    ],
    'bodies' => [
        UnitOfMeasurePostData::class => [UnitOfMeasurePostData::class, Cin7Payloads::load('ref/unit', 'post.request')],
        UnitOfMeasurePutData::class => [UnitOfMeasurePutData::class, Cin7Payloads::load('ref/unit', 'put.request')],
    ],
    'missing' => [
        'unit of measure POST without Name' => [UnitOfMeasurePostData::class, []],
        'unit of measure PUT without ID' => [UnitOfMeasurePutData::class, Arr::except(Cin7Payloads::load('ref/unit', 'put.request'), 'ID')],
        'unit of measure without Name' => [UnitOfMeasureData::class, Arr::except(Cin7Payloads::load('ref/unit', 'post.response'), 'Name')],
    ],
    'required' => [
        UnitOfMeasureData::class => ['Name'],
        UnitOfMeasurePostData::class => ['Name'],
        UnitOfMeasurePutData::class => ['Name', 'ID'],
    ],
];
