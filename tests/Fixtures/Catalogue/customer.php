<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `customer`; tests/Catalogue.php merges every file's rows by kind.

$customer = ['Name' => 'ACME', 'LastModifiedOn' => '2020-01-01', 'ChildCustomers' => [['ID' => 'c']], 'ProductPrices' => [['Price' => 1, 'ProductName' => 'Bread'], 'raw']];
$customerSent = ['Name' => 'ACME', 'ProductPrices' => [['Price' => 1], 'raw']];

return [
    'requests' => [
        GetCustomer::class => [
            GetCustomer::class,
            [],
            Method::GET,
            '/ExternalApi/v2/customer',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        PostCustomer::class => [
            PostCustomer::class,
            [['Name' => 'ACME']],
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Name' => 'ACME'],
        ],
        PutCustomer::class => [
            PutCustomer::class,
            [['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME']],
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
        ],
        PostCustomer::class . ' with data' => [
            PostCustomer::class,
            [fn (): CustomerData => CustomerData::from(['Name' => 'ACME', 'AdditionalAttribute10' => 'x', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']]])],
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Name' => 'ACME', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']], 'AdditionalAttribute10' => 'x'],
        ],
        PutCustomer::class . ' with data' => [
            PutCustomer::class,
            [fn (): CustomerData => CustomerData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxNumber' => null])],
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'],
        ],
    ],
    'resources' => [
        'customer get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->get(),
            GetCustomer::class,
            Method::GET,
            '/ExternalApi/v2/customer',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'customer paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->paginate()->current(),
            GetCustomer::class,
            Method::GET,
            '/ExternalApi/v2/customer',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'customer post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(['Name' => 'ACME']),
            PostCustomer::class,
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Name' => 'ACME'],
        ],
        'customer post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(CustomerData::from(['Name' => 'ACME'])),
            PostCustomer::class,
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Name' => 'ACME'],
        ],
        'customer put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(CustomerData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'])),
            PutCustomer::class,
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
        ],
        'customer put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME']),
            PutCustomer::class,
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'ACME'],
        ],
    ],
    'dtos' => [
        GetCustomer::class => [GetCustomer::class, [], Cin7Payloads::customerExample(), CustomerData::class, 'CustomerList'],
        PostCustomer::class => [PostCustomer::class, [[]], Cin7Payloads::customerSaved(), CustomerData::class, 'CustomerList.0'],
        PutCustomer::class => [PutCustomer::class, [[]], Cin7Payloads::customerSaved(), CustomerData::class, 'CustomerList.0'],
    ],
    'omitted' => [
        PostCustomer::class => [PostCustomer::class, $customer, $customerSent],
        PutCustomer::class => [PutCustomer::class, $customer, $customerSent],
    ],
];
