<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/tax`; tests/Catalogue.php merges every file's rows by kind.

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
            [fn (): TaxData => TaxData::from(['Name' => 'VAT'])],
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            ['Name' => 'VAT'],
        ],
        PutTax::class . ' with data' => [
            PutTax::class,
            [fn (): TaxData => TaxData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'])],
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
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
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(TaxData::from(['Name' => 'VAT'])),
            PostTax::class,
            Method::POST,
            '/ExternalApi/v2/ref/tax',
            [],
            ['Name' => 'VAT'],
        ],
        'ref tax put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(TaxData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'])),
            PutTax::class,
            Method::PUT,
            '/ExternalApi/v2/ref/tax',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'],
        ],
    ],
    'dtos' => [
        GetTax::class => [GetTax::class, [], Cin7Payloads::taxList(), TaxData::class, 'TaxRuleList'],
        PostTax::class => [PostTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
        PutTax::class => [PutTax::class, [[]], Cin7Payloads::taxSaved(), TaxData::class, 'TaxRuleList.0'],
    ],
    'omitted' => [
        PostTax::class => [PostTax::class, ['Name' => 'VAT', 'TaxPercent' => 20], ['Name' => 'VAT']],
        PutTax::class => [PutTax::class, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxPercent' => 20], ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1']],
    ],
];
