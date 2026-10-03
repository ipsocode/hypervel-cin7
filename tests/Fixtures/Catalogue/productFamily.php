<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyData;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyPostData;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyProductLineData;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyPutData;
use Ipsocode\Cin7\Requests\ProductFamily\GetProductFamily;
use Ipsocode\Cin7\Requests\ProductFamily\PostProductFamily;
use Ipsocode\Cin7\Requests\ProductFamily\PutProductFamily;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `productFamily`; tests/Catalogue.php merges every file's rows by kind.

// The fields every product family requires, and a product line with the fields Cin7 ignores.
$fields = ['SKU' => 'Test', 'Name' => 'Test', 'Category' => 'Other', 'CostingMethod' => 'FIFO', 'DefaultLocation' => 'Main Warehouse', 'UOM' => 'Item', 'Option1Name' => 'Test 1'];
$familyId = '76755c09-60de-483c-a63b-18fd12c2d932';
$productId = 'ce9a6504-4207-4001-b430-749bf11fdc4f';
$line = ['ID' => $productId, 'Option1' => '3', 'SKU' => 'GB1-White', 'Name' => 'Golf balls - white single'];

return [
    'requests' => [
        GetProductFamily::class => [
            GetProductFamily::class,
            ['id' => $familyId, 'name' => 'Test', 'sku' => 'T', 'modifiedSince' => '2017-12-01T00:00:00Z'],
            Method::GET,
            '/ExternalApi/v2/productFamily',
            ['ID' => $familyId, 'Name' => 'Test', 'Sku' => 'T', 'ModifiedSince' => '2017-12-01T00:00:00Z', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostProductFamily::class => [
            PostProductFamily::class,
            [['SKU' => 'Test', 'Products' => [$line]]],
            Method::POST,
            '/ExternalApi/v2/productFamily',
            [],
            ['SKU' => 'Test', 'Products' => [['ID' => $productId, 'Option1' => '3']]],
        ],
        PutProductFamily::class => [
            PutProductFamily::class,
            [['ID' => $familyId, 'Products' => [$line]]],
            Method::PUT,
            '/ExternalApi/v2/productFamily',
            [],
            ['ID' => $familyId, 'Products' => [['ID' => $productId, 'Option1' => '3']]],
        ],
        PostProductFamily::class . ' with data' => [
            PostProductFamily::class,
            [fn (): ProductFamilyPostData => ProductFamilyPostData::from([...$fields, 'Products' => [$line], 'PriceTier1' => 1.5])],
            Method::POST,
            '/ExternalApi/v2/productFamily',
            [],
            ['Products' => [['ID' => $productId, 'Option1' => '3']], ...$fields, 'PriceTier1' => 1.5],
        ],
        PutProductFamily::class . ' with data' => [
            PutProductFamily::class,
            [fn (): ProductFamilyPutData => ProductFamilyPutData::from([...$fields, 'ID' => $familyId, 'Brand' => 'Acme'])],
            Method::PUT,
            '/ExternalApi/v2/productFamily',
            [],
            ['ID' => $familyId, 'Brand' => 'Acme', ...$fields],
        ],
    ],
    'resources' => [
        'productFamily get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->get(sku: 'T'),
            GetProductFamily::class,
            Method::GET,
            '/ExternalApi/v2/productFamily',
            ['Sku' => 'T', 'page' => 1, 'limit' => 100],
            null,
        ],
        'productFamily paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->paginate(name: 'Test')->current(),
            GetProductFamily::class,
            Method::GET,
            '/ExternalApi/v2/productFamily',
            ['Name' => 'Test', 'page' => 1, 'limit' => 100],
            null,
        ],
        'productFamily post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->post(['SKU' => 'Test']),
            PostProductFamily::class,
            Method::POST,
            '/ExternalApi/v2/productFamily',
            [],
            ['SKU' => 'Test'],
        ],
        'productFamily post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->post(ProductFamilyPostData::from($fields)),
            PostProductFamily::class,
            Method::POST,
            '/ExternalApi/v2/productFamily',
            [],
            $fields,
        ],
        'productFamily put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->put(['ID' => $familyId]),
            PutProductFamily::class,
            Method::PUT,
            '/ExternalApi/v2/productFamily',
            [],
            ['ID' => $familyId],
        ],
        'productFamily put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->put(ProductFamilyPutData::from([...$fields, 'ID' => $familyId])),
            PutProductFamily::class,
            Method::PUT,
            '/ExternalApi/v2/productFamily',
            [],
            ['ID' => $familyId, ...$fields],
        ],
    ],
    'dtos' => [
        GetProductFamily::class => [GetProductFamily::class, [], Cin7Payloads::load('productFamily', 'get.response'), ProductFamilyData::class, 'ProductFamilies'],
        PostProductFamily::class => [PostProductFamily::class, [[]], Cin7Payloads::load('productFamily', 'post.response'), ProductFamilyData::class, 'ProductFamilies.0'],
        PutProductFamily::class => [PutProductFamily::class, [[]], Cin7Payloads::load('productFamily', 'put.response'), ProductFamilyData::class, 'ProductFamilies.0'],
    ],
    'bodies' => [
        ProductFamilyPostData::class => [ProductFamilyPostData::class, Cin7Payloads::load('productFamily', 'post.request')],
        ProductFamilyPutData::class => [ProductFamilyPutData::class, Cin7Payloads::load('productFamily', 'put.request')],
    ],
    'missing' => [
        'family POST without Option1Name' => [ProductFamilyPostData::class, Arr::except(Cin7Payloads::load('productFamily', 'post.request'), 'Option1Name')],
        'family PUT without ID' => [ProductFamilyPutData::class, Arr::except(Cin7Payloads::load('productFamily', 'put.request'), 'ID')],
        'family without UOM' => [ProductFamilyData::class, Arr::except(Cin7Payloads::load('productFamily', 'get.response')['ProductFamilies'][0], 'UOM')],
        'family product line without Option1' => [ProductFamilyProductLineData::class, ['ID' => 'ce9a6504-4207-4001-b430-749bf11fdc4f']],
    ],
    'required' => [
        ProductFamilyData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'DefaultLocation', 'UOM', 'Option1Name'],
        ProductFamilyPostData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'DefaultLocation', 'UOM', 'Option1Name'],
        ProductFamilyPutData::class => ['SKU', 'Name', 'Category', 'CostingMethod', 'DefaultLocation', 'UOM', 'Option1Name', 'ID'],
        ProductFamilyProductLineData::class => ['ID', 'Option1'],
    ],
];
