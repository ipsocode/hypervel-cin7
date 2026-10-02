<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `product`; tests/Catalogue.php merges every file's rows by kind.

$product = [
    'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1',
    'Type' => 'Stock',
    'SKU' => 'Bread',
    'AverageCost' => 1.5,
    'LastModifiedOn' => '2020-01-01',
    'BOMType' => 'None',
    'Suppliers' => [['SupplierName' => 'S', 'Currency' => 'USD']],
    'BillOfMaterialsProducts' => [['Name' => 'Flour', 'Quantity' => 1]],
    'CustomPrices' => [['Price' => 2, 'ProductName' => 'Bread']],
];
$productSent = [
    'SKU' => 'Bread',
    'Suppliers' => [['SupplierName' => 'S']],
    'BillOfMaterialsProducts' => [['Quantity' => 1]],
    'CustomPrices' => [['Price' => 2]],
];

return [
    'requests' => [
        GetProduct::class => [
            GetProduct::class,
            [],
            Method::GET,
            '/ExternalApi/v2/product',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        PostProduct::class => [
            PostProduct::class,
            [['Name' => 'Widget']],
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['Name' => 'Widget'],
        ],
        PutProduct::class => [
            PutProduct::class,
            [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget']],
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget'],
        ],
        PostProduct::class . ' with data' => [
            PostProduct::class,
            [fn (): ProductData => ProductData::from(['SKU' => 'Bread', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['LocationName' => 'Main Warehouse', 'PickZones' => 'test']]])],
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['SKU' => 'Bread', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['LocationName' => 'Main Warehouse', 'PickZones' => 'test']]],
        ],
        PutProduct::class . ' with data' => [
            PutProduct::class,
            [fn (): ProductData => ProductData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false])],
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false],
        ],
    ],
    'resources' => [
        'product post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->post(ProductData::from(['SKU' => 'Bread'])),
            PostProduct::class,
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['SKU' => 'Bread'],
        ],
        'product put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->put(ProductData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread'])),
            PutProduct::class,
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread'],
        ],
        'product get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->get(),
            GetProduct::class,
            Method::GET,
            '/ExternalApi/v2/product',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'product paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->paginate()->current(),
            GetProduct::class,
            Method::GET,
            '/ExternalApi/v2/product',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'product post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->post(['Name' => 'Widget']),
            PostProduct::class,
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['Name' => 'Widget'],
        ],
        'product put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget']),
            PutProduct::class,
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'Widget'],
        ],
    ],
    'dtos' => [
        GetProduct::class => [GetProduct::class, [], Cin7Payloads::productExample(), ProductData::class, 'Products'],
        PostProduct::class => [PostProduct::class, [[]], Cin7Payloads::productSaved(), ProductData::class, 'Products.0'],
        PutProduct::class => [PutProduct::class, [['ID' => 'guid-1']], Cin7Payloads::productSaved(), ProductData::class, 'Products.0'],
    ],
    'omitted' => [
        PostProduct::class => [PostProduct::class, $product, ['Type' => 'Stock'] + $productSent],
        PutProduct::class => [PutProduct::class, $product, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'] + $productSent],
        'a non-list value is left alone' => [PostProduct::class, ['Suppliers' => 'raw'], ['Suppliers' => 'raw']],
    ],
];
