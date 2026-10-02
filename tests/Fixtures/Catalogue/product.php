<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\BillOfMaterialProductData;
use Ipsocode\Cin7\Data\Product\BillOfMaterialServiceData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Product\ProductPostData;
use Ipsocode\Cin7\Data\Product\ProductPutData;
use Ipsocode\Cin7\Data\Product\ReorderLevelData;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `product`; tests/Catalogue.php merges every file's rows by kind.

// The fields every product requires.
$fields = ['SKU' => 'Bread', 'Name' => 'Baked Bread', 'Category' => 'Other', 'CostingMethod' => 'FIFO', 'UOM' => 'Item', 'Status' => 'Active'];

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
            [fn (): ProductPostData => ProductPostData::from([...$fields, 'Type' => 'Stock', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['LocationName' => 'Main Warehouse', 'PickZones' => 'test']]])],
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['Type' => 'Stock', 'PriceTiers' => ['Tier 1' => 8.0], 'ReorderLevels' => [['PickZones' => 'test', 'LocationName' => 'Main Warehouse']], ...$fields],
        ],
        PutProduct::class . ' with data' => [
            PutProduct::class,
            [fn (): ProductPutData => ProductPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false])],
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Sellable' => false, ...$fields],
        ],
    ],
    'resources' => [
        'product post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->post(ProductPostData::from([...$fields, 'Type' => 'Stock'])),
            PostProduct::class,
            Method::POST,
            '/ExternalApi/v2/product',
            [],
            ['Type' => 'Stock', ...$fields],
        ],
        'product put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->put(ProductPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'])),
            PutProduct::class,
            Method::PUT,
            '/ExternalApi/v2/product',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ...$fields],
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
    'bodies' => [
        ProductPostData::class => [ProductPostData::class, Cin7Payloads::load('product', 'post.request')],
        ProductPutData::class => [ProductPutData::class, Cin7Payloads::load('product', 'put.request')],
    ],
    'missing' => [
        'product POST without Type' => [ProductPostData::class, Arr::except(Cin7Payloads::load('product', 'post.request'), 'Type')],
        'product PUT without ID' => [ProductPutData::class, Arr::except(Cin7Payloads::load('product', 'put.request'), 'ID')],
        'product without UOM' => [ProductData::class, Arr::except(Cin7Payloads::productExample()['Products'][0], 'UOM')],
        'reorder level without PickZones' => [ReorderLevelData::class, ['LocationName' => 'Main Warehouse']],
        'component without Quantity' => [BillOfMaterialProductData::class, ['ProductCode' => 'GB1-White']],
        'service without Quantity' => [BillOfMaterialServiceData::class, ['Name' => 'Half day training - Microsoft Office']],
    ],
    'required' => [
        ProductData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'UOM', 'Status'],
        ProductPostData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'UOM', 'Status', 'Type'],
        ProductPutData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'UOM', 'Status', 'ID'],
        ReorderLevelData::class => ['PickZones'],
        BillOfMaterialProductData::class => ['Quantity'],
        BillOfMaterialServiceData::class => ['Quantity'],
    ],
    'omitted' => [
        PostProduct::class => [PostProduct::class, $product, ['Type' => 'Stock'] + $productSent],
        PutProduct::class => [PutProduct::class, $product, ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'] + $productSent],
        'a non-list value is left alone' => [PostProduct::class, ['Suppliers' => 'raw'], ['Suppliers' => 'raw']],
    ],
];
