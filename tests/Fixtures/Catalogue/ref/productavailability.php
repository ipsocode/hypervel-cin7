<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\ProductAvailability\ProductAvailabilityData;
use Ipsocode\Cin7\Requests\Ref\ProductAvailability\GetProductAvailability;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/productavailability`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetProductAvailability::class => [
            GetProductAvailability::class,
            ['id' => 'e2b4d0a3-1111-4a3b-8d0e-5a0c6a8d1111', 'name' => 'Bread', 'sku' => 'BR1', 'location' => 'Main Warehouse', 'batch' => 'B1', 'category' => 'Other'],
            Method::GET,
            '/ExternalApi/v2/ref/productavailability',
            ['ID' => 'e2b4d0a3-1111-4a3b-8d0e-5a0c6a8d1111', 'Name' => 'Bread', 'Sku' => 'BR1', 'Location' => 'Main Warehouse', 'Batch' => 'B1', 'Category' => 'Other', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'ref productAvailability get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->productAvailability()->get(sku: 'BR1'),
            GetProductAvailability::class,
            Method::GET,
            '/ExternalApi/v2/ref/productavailability',
            ['Sku' => 'BR1', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref productAvailability paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->productAvailability()->paginate(location: 'Main Warehouse')->current(),
            GetProductAvailability::class,
            Method::GET,
            '/ExternalApi/v2/ref/productavailability',
            ['Location' => 'Main Warehouse', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetProductAvailability::class => [GetProductAvailability::class, [], Cin7Payloads::load('ref/productavailability', 'get.response'), ProductAvailabilityData::class, 'ProductAvailabilityList'],
    ],
];
