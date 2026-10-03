<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Requests\Production\ResourceList\GetProductionResourceList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/resourceList`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionResourceList::class => [
            GetProductionResourceList::class,
            ['name' => 'x', 'onlyActive' => true],
            Method::GET,
            '/ExternalApi/v2/production/resourceList',
            ['Name' => 'x', 'OnlyActive' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'production resourceList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resourceList()->get(name: 'x', onlyActive: true),
            GetProductionResourceList::class,
            Method::GET,
            '/ExternalApi/v2/production/resourceList',
            ['Name' => 'x', 'OnlyActive' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
        'production resourceList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resourceList()->paginate()->current(),
            GetProductionResourceList::class,
            Method::GET,
            '/ExternalApi/v2/production/resourceList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetProductionResourceList::class => [GetProductionResourceList::class, [], Cin7Payloads::load('production/resourceList', 'get.response'), ResourceData::class, 'Resources'],
    ],
];
