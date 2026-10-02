<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Requests\Ref\Customer\Credits\GetCustomerCredits;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/customer/credits`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetCustomerCredits::class => [
            GetCustomerCredits::class,
            [],
            Method::GET,
            '/ExternalApi/v2/ref/customer/credits',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'ref customer credits get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->get(['CustomerID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShowUsedCredits' => true]),
            GetCustomerCredits::class,
            Method::GET,
            '/ExternalApi/v2/ref/customer/credits',
            ['CustomerID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'ShowUsedCredits' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref customer credits paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->paginate()->current(),
            GetCustomerCredits::class,
            Method::GET,
            '/ExternalApi/v2/ref/customer/credits',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetCustomerCredits::class => [
            GetCustomerCredits::class,
            [],
            Cin7Payloads::customerCreditsExample(),
            CustomerCreditData::class,
            'CustomerCredits',
        ],
    ],
];
