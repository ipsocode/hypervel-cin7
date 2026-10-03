<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypeData;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypePostData;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypePutData;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\GetFixedAssetType;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\PostFixedAssetType;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\PutFixedAssetType;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/fixedassettype`; tests/Catalogue.php merges every file's rows by kind.

// The fields every ref fixedassettype requires.
$fields = ['Name' => 'Third FixedAssetType', 'DepreciationMethod' => 'No Depreciation', 'AveragingMethod' => 'Full Month', 'AssetAccountCode' => '710', 'AccumulatedDepreciationAccountCode' => '710'];

return [
    'requests' => [
        GetFixedAssetType::class => [
            GetFixedAssetType::class,
            ['fixedAssetTypeId' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'name' => 'Third'],
            Method::GET,
            '/ExternalApi/v2/ref/fixedassettype',
            ['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'Name' => 'Third', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostFixedAssetType::class => [
            PostFixedAssetType::class,
            [['Name' => 'Third FixedAssetType', 'Rate' => 10]],
            Method::POST,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['Name' => 'Third FixedAssetType', 'Rate' => 10],
        ],
        PutFixedAssetType::class => [
            PutFixedAssetType::class,
            [['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'Rate' => 15]],
            Method::PUT,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'Rate' => 15],
        ],
        PostFixedAssetType::class . ' with data' => [
            PostFixedAssetType::class,
            [fn (): FixedAssetTypePostData => FixedAssetTypePostData::from([...$fields, 'Rate' => 10, 'DepreciationExpenseAccountCode' => '400'])],
            Method::POST,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['Rate' => 10.0, 'DepreciationExpenseAccountCode' => '400', ...$fields],
        ],
        PutFixedAssetType::class . ' with data' => [
            PutFixedAssetType::class,
            [fn (): FixedAssetTypePutData => FixedAssetTypePutData::from([...$fields, 'FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'EffectiveLife' => 2])],
            Method::PUT,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'EffectiveLife' => 2.0, ...$fields],
        ],
    ],
    'resources' => [
        'ref fixedAssetType get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->get(),
            GetFixedAssetType::class,
            Method::GET,
            '/ExternalApi/v2/ref/fixedassettype',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref fixedAssetType paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->paginate(name: 'A')->current(),
            GetFixedAssetType::class,
            Method::GET,
            '/ExternalApi/v2/ref/fixedassettype',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref fixedAssetType post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->post(['Name' => 'Third FixedAssetType', 'Rate' => 10]),
            PostFixedAssetType::class,
            Method::POST,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['Name' => 'Third FixedAssetType', 'Rate' => 10],
        ],
        'ref fixedAssetType post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->post(FixedAssetTypePostData::from([...$fields, 'Rate' => 10])),
            PostFixedAssetType::class,
            Method::POST,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['Rate' => 10.0, ...$fields],
        ],
        'ref fixedAssetType put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->put(['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'Rate' => 15]),
            PutFixedAssetType::class,
            Method::PUT,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'Rate' => 15],
        ],
        'ref fixedAssetType put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->fixedAssetType()->put(FixedAssetTypePutData::from([...$fields, 'FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'EffectiveLife' => 2])),
            PutFixedAssetType::class,
            Method::PUT,
            '/ExternalApi/v2/ref/fixedassettype',
            [],
            ['FixedAssetTypeID' => 'e410a45b-a4c1-47cb-aaad-a6714112ef56', 'EffectiveLife' => 2.0, ...$fields],
        ],
    ],
    'dtos' => [
        GetFixedAssetType::class => [GetFixedAssetType::class, [], Cin7Payloads::load('ref/fixedassettype', 'get.response'), FixedAssetTypeData::class, 'FixedAssetTypeList'],
        PostFixedAssetType::class => [PostFixedAssetType::class, [[]], Cin7Payloads::load('ref/fixedassettype', 'post.response'), FixedAssetTypeData::class, 'FixedAssetTypeList.0'],
        PutFixedAssetType::class => [PutFixedAssetType::class, [[]], Cin7Payloads::load('ref/fixedassettype', 'put.response'), FixedAssetTypeData::class, 'FixedAssetTypeList.0'],
    ],
    'bodies' => [
        FixedAssetTypePostData::class => [FixedAssetTypePostData::class, Cin7Payloads::load('ref/fixedassettype', 'post.request')],
        FixedAssetTypePutData::class => [FixedAssetTypePutData::class, Cin7Payloads::load('ref/fixedassettype', 'put.request')],
    ],
    'missing' => [
        'ref fixedassettype POST without Name' => [FixedAssetTypePostData::class, Arr::except(Cin7Payloads::load('ref/fixedassettype', 'post.request'), 'Name')],
        'ref fixedassettype PUT without FixedAssetTypeID' => [FixedAssetTypePutData::class, Arr::except(Cin7Payloads::load('ref/fixedassettype', 'put.request'), 'FixedAssetTypeID')],
        'ref fixedassettype without Name' => [FixedAssetTypeData::class, Arr::except(Cin7Payloads::load('ref/fixedassettype', 'get.response')['FixedAssetTypeList'][0], 'Name')],
    ],
    'required' => [
        FixedAssetTypeData::class => ['Name', 'DepreciationMethod', 'AveragingMethod', 'AssetAccountCode', 'AccumulatedDepreciationAccountCode'],
        FixedAssetTypePostData::class => ['Name', 'DepreciationMethod', 'AveragingMethod', 'AssetAccountCode', 'AccumulatedDepreciationAccountCode'],
        FixedAssetTypePutData::class => ['Name', 'DepreciationMethod', 'AveragingMethod', 'AssetAccountCode', 'AccumulatedDepreciationAccountCode', 'FixedAssetTypeID'],
    ],
];
