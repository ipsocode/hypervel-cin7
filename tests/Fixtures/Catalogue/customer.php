<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Customer\CustomerAddressData;
use Ipsocode\Cin7\Data\Customer\CustomerContactData;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\Customer\CustomerPostData;
use Ipsocode\Cin7\Data\Customer\CustomerPutData;
use Ipsocode\Cin7\Data\ProductPriceData;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `customer`; tests/Catalogue.php merges every file's rows by kind.

// The fields every customer requires.
$fields = ['Name' => 'ACME', 'Currency' => 'GBP', 'PaymentTerm' => '30 days', 'AccountReceivable' => '610', 'RevenueAccount' => '200', 'TaxRule' => 'Tax Exempt'];

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
            [fn (): CustomerPostData => CustomerPostData::from([...$fields, 'Status' => 'Active', 'AdditionalAttribute10' => 'x', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']]])],
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Status' => 'Active', 'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']], ...$fields, 'AdditionalAttribute10' => 'x'],
        ],
        PutCustomer::class . ' with data' => [
            PutCustomer::class,
            [fn (): CustomerPutData => CustomerPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxNumber' => null])],
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ...$fields],
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
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(CustomerPostData::from([...$fields, 'Status' => 'Active'])),
            PostCustomer::class,
            Method::POST,
            '/ExternalApi/v2/customer',
            [],
            ['Status' => 'Active', ...$fields],
        ],
        'customer put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(CustomerPutData::from([...$fields, 'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'])),
            PutCustomer::class,
            Method::PUT,
            '/ExternalApi/v2/customer',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', ...$fields],
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
    'bodies' => [
        CustomerPostData::class => [CustomerPostData::class, Cin7Payloads::load('customer', 'post.request')],
        CustomerPutData::class => [CustomerPutData::class, Cin7Payloads::load('customer', 'put.request')],
    ],
    'missing' => [
        'customer POST without Status' => [CustomerPostData::class, Arr::except(Cin7Payloads::load('customer', 'post.request'), 'Status')],
        'customer PUT without ID' => [CustomerPutData::class, Arr::except(Cin7Payloads::load('customer', 'put.request'), 'ID')],
        'customer without TaxRule' => [CustomerData::class, Arr::except(Cin7Payloads::customerExample()['CustomerList'][0], 'TaxRule')],
        'customer address without Type' => [CustomerAddressData::class, ['Line1' => '1 High St', 'Country' => 'UK']],
        'customer contact without Name' => [CustomerContactData::class, ['Email' => 'accounts@example.com']],
        'product price without Price' => [ProductPriceData::class, ['ProductSKU' => 'Screws-SKU - 002']],
    ],
    'required' => [
        CustomerData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountReceivable', 'RevenueAccount', 'TaxRule', 'ID'],
        CustomerPostData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountReceivable', 'RevenueAccount', 'TaxRule', 'Status'],
        CustomerPutData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountReceivable', 'RevenueAccount', 'TaxRule', 'ID'],
        CustomerAddressData::class => ['Line1', 'Country', 'Type'],
        CustomerContactData::class => ['Name'],
        ProductPriceData::class => ['Price'],
    ],
    'omitted' => [
        PostCustomer::class => [PostCustomer::class, $customer, $customerSent],
        PutCustomer::class => [PutCustomer::class, $customer, $customerSent],
    ],
];
