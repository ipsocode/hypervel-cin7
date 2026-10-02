<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\ProductSuppliers\ProductSuppliersData;
use Ipsocode\Cin7\Requests\ProductSuppliers\DeleteProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\GetProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PostProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PutProductSuppliers;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `product-suppliers`; tests/Catalogue.php merges every file's rows by kind.

$productId = '2fcb8c53-9183-4bd3-aefa-b8f78167e159';
$supplierId = '33a22f89-c8a2-43e1-8b4a-f677b39f521b';
$fields = ['ProductSuppliers' => [['SupplierID' => $supplierId, 'ProductID' => $productId, 'Cost' => 1.0]]];

return [
    'requests' => [
        GetProductSuppliers::class => [
            GetProductSuppliers::class,
            [$productId],
            Method::GET,
            '/ExternalApi/v2/product-suppliers',
            ['ProductID' => $productId],
            null,
        ],
        PostProductSuppliers::class => [
            PostProductSuppliers::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        PutProductSuppliers::class => [
            PutProductSuppliers::class,
            [$fields],
            Method::PUT,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        DeleteProductSuppliers::class => [
            DeleteProductSuppliers::class,
            [$productId, $supplierId],
            Method::DELETE,
            '/ExternalApi/v2/product-suppliers',
            ['ProductID' => $productId, 'SupplierID' => $supplierId],
            null,
        ],
        PostProductSuppliers::class . ' with data' => [
            PostProductSuppliers::class,
            [fn (): ProductSuppliersData => ProductSuppliersData::from($fields)],
            Method::POST,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        PutProductSuppliers::class . ' with data' => [
            PutProductSuppliers::class,
            [fn (): ProductSuppliersData => ProductSuppliersData::from($fields)],
            Method::PUT,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
    ],
    'resources' => [
        'productSuppliers get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->get($productId),
            GetProductSuppliers::class,
            Method::GET,
            '/ExternalApi/v2/product-suppliers',
            ['ProductID' => $productId],
            null,
        ],
        'productSuppliers post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->post($fields),
            PostProductSuppliers::class,
            Method::POST,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        'productSuppliers post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->post(ProductSuppliersData::from($fields)),
            PostProductSuppliers::class,
            Method::POST,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        'productSuppliers put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->put($fields),
            PutProductSuppliers::class,
            Method::PUT,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        'productSuppliers put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->put(ProductSuppliersData::from($fields)),
            PutProductSuppliers::class,
            Method::PUT,
            '/ExternalApi/v2/product-suppliers',
            [],
            $fields,
        ],
        'productSuppliers delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productSuppliers()->delete($productId, $supplierId),
            DeleteProductSuppliers::class,
            Method::DELETE,
            '/ExternalApi/v2/product-suppliers',
            ['ProductID' => $productId, 'SupplierID' => $supplierId],
            null,
        ],
    ],
    'dtos' => [
        GetProductSuppliers::class => [GetProductSuppliers::class, [$productId], Cin7Payloads::load('product-suppliers', 'get.response'), ProductSuppliersData::class, ''],
    ],
    'bodies' => [
        ProductSuppliersData::class . ' POST' => [ProductSuppliersData::class, Cin7Payloads::load('product-suppliers', 'post.request')],
        ProductSuppliersData::class . ' PUT' => [ProductSuppliersData::class, Cin7Payloads::load('product-suppliers', 'put.request')],
    ],
    'missing' => [
        'product suppliers without ProductSuppliers' => [ProductSuppliersData::class, Arr::except(Cin7Payloads::load('product-suppliers', 'post.request'), 'ProductSuppliers')],
    ],
    'required' => [
        ProductSuppliersData::class => ['ProductSuppliers'],
    ],
];
