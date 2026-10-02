<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Tax\TaxComponentData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxPostData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxPutData;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/tax`; tests/Catalogue.php merges every file's rows by kind.

// The fields every tax rule requires.
$fields = ['Name' => 'VAT', 'Account' => '820', 'IsActive' => true, 'TaxInclusive' => false];

return [
    'requests' => [
        GetTax::class => [
            GetTax::class,
            [],
            Method::GET,
            '/ExternalApi/v2/ref/tax',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        PostTax::class => [
            PostTax::class,
            [['Name' => 'VAT']],
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            ['Name' => 'VAT'],
        ],
        PutTax::class => [
            PutTax::class,
            [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT']],
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
        ],
        PostTax::class . ' with data' => [
            PostTax::class,
            [fn (): TaxPostData => TaxPostData::from($fields)],
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            $fields,
        ],
        PutTax::class . ' with data' => [
            PutTax::class,
            [fn (): TaxPutData => TaxPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'])],
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ...$fields],
        ],
    ],
    'resources' => [
        'ref tax get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->get(),
            GetTax::class,
            Method::GET,
            '/ExternalApi/v2/ref/tax',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref tax paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->paginate()->current(),
            GetTax::class,
            Method::GET,
            '/ExternalApi/v2/ref/tax',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref tax post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(['Name' => 'VAT']),
            PostTax::class,
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            ['Name' => 'VAT'],
        ],
        'ref tax put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT']),
            PutTax::class,
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
        ],
        'ref tax post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(TaxPostData::from($fields)),
            PostTax::class,
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            $fields,
        ],
        'ref tax put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(TaxPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'])),
            PutTax::class,
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ...$fields],
        ],
    ],
    'dtos' => [
        GetTax::class => [GetTax::class, [], Cin7Payloads::taxList(), TaxData::class, 'TaxRuleList'],
        PostTax::class => [PostTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
        PutTax::class => [PutTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
    ],
    'bodies' => [
        TaxPostData::class => [TaxPostData::class, Cin7Payloads::load('ref/tax', 'post.request')],
        TaxPutData::class => [TaxPutData::class, Cin7Payloads::load('ref/tax', 'put.request')],
    ],
    'missing' => [
        'tax PUT without ID' => [TaxPutData::class, Arr::except(Cin7Payloads::load('ref/tax', 'put.request'), 'ID')],
        'tax without Account' => [TaxData::class, Arr::except(Cin7Payloads::taxList()['TaxRuleList'][0], 'Account')],
        'tax component without ComponentOrder' => [TaxComponentData::class, ['Name' => 'Tax', 'Percent' => 10, 'AccountCode' => '820']],
    ],
    'required' => [
        TaxData::class => ['Name', 'Account', 'IsActive', 'TaxInclusive'],
        TaxPostData::class => ['Name', 'Account', 'IsActive', 'TaxInclusive'],
        TaxPutData::class => ['Name', 'Account', 'IsActive', 'TaxInclusive', 'ID'],
        TaxComponentData::class => ['Name', 'Percent', 'AccountCode', 'ComponentOrder'],
    ],
    'omitted' => [
        PostTax::class => [PostTax::class, ['Name' => 'VAT', 'TaxPercent' => 20], ['Name' => 'VAT']],
        PutTax::class => [PutTax::class, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxPercent' => 20], ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1']],
    ],
];
