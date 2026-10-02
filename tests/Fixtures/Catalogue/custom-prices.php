<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\CustomPrices\CustomPricesData;
use Ipsocode\Cin7\Requests\CustomPrices\DeleteCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PostCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PutCustomPrices;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `custom-prices`; tests/Catalogue.php merges every file's rows by kind.

$productId = '2fcb8c53-9183-4bd3-aefa-b8f78167e159';
$customerId = '201a76af-9da8-4cb0-ab85-5570f36edff6';
$fields = ['CustomPrices' => [['CustomerID' => $customerId, 'ProductSKU' => 'Screws-SKU - 001', 'Price' => 1.1]]];
$sent = ['CustomPrices' => [['Price' => 1.1, 'CustomerID' => $customerId, 'ProductSKU' => 'Screws-SKU - 001']]];

return [
    'requests' => [
        PostCustomPrices::class => [
            PostCustomPrices::class,
            [$fields],
            Method::POST,
            '/ExternalApi/v2/custom-prices',
            [],
            $fields,
        ],
        PutCustomPrices::class => [
            PutCustomPrices::class,
            [$fields],
            Method::PUT,
            '/ExternalApi/v2/custom-prices',
            [],
            $fields,
        ],
        DeleteCustomPrices::class => [
            DeleteCustomPrices::class,
            [$productId, $customerId],
            Method::DELETE,
            '/ExternalApi/v2/custom-prices',
            ['ProductID' => $productId, 'CustomerID' => $customerId],
            null,
        ],
        PostCustomPrices::class . ' with data' => [
            PostCustomPrices::class,
            [fn (): CustomPricesData => CustomPricesData::from($fields)],
            Method::POST,
            '/ExternalApi/v2/custom-prices',
            [],
            $sent,
        ],
        PutCustomPrices::class . ' with data' => [
            PutCustomPrices::class,
            [fn (): CustomPricesData => CustomPricesData::from($fields)],
            Method::PUT,
            '/ExternalApi/v2/custom-prices',
            [],
            $sent,
        ],
    ],
    'resources' => [
        'customPrices post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customPrices()->post($fields),
            PostCustomPrices::class,
            Method::POST,
            '/ExternalApi/v2/custom-prices',
            [],
            $fields,
        ],
        'customPrices post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customPrices()->post(CustomPricesData::from($fields)),
            PostCustomPrices::class,
            Method::POST,
            '/ExternalApi/v2/custom-prices',
            [],
            $sent,
        ],
        'customPrices put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customPrices()->put($fields),
            PutCustomPrices::class,
            Method::PUT,
            '/ExternalApi/v2/custom-prices',
            [],
            $fields,
        ],
        'customPrices put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customPrices()->put(CustomPricesData::from($fields)),
            PutCustomPrices::class,
            Method::PUT,
            '/ExternalApi/v2/custom-prices',
            [],
            $sent,
        ],
        'customPrices delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customPrices()->delete($productId, $customerId),
            DeleteCustomPrices::class,
            Method::DELETE,
            '/ExternalApi/v2/custom-prices',
            ['ProductID' => $productId, 'CustomerID' => $customerId],
            null,
        ],
    ],
    'bodies' => [
        CustomPricesData::class . ' POST' => [CustomPricesData::class, Cin7Payloads::load('custom-prices', 'post.request')],
        CustomPricesData::class . ' PUT' => [CustomPricesData::class, Cin7Payloads::load('custom-prices', 'put.request')],
    ],
    'missing' => [
        'custom prices without CustomPrices' => [CustomPricesData::class, Arr::except(Cin7Payloads::load('custom-prices', 'post.request'), 'CustomPrices')],
    ],
    'required' => [
        CustomPricesData::class => ['CustomPrices'],
    ],
];
