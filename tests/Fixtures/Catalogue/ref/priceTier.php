<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\PriceTier\PriceTierData;
use Ipsocode\Cin7\Requests\Ref\PriceTier\GetPriceTier;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/priceTier`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetPriceTier::class => [
            GetPriceTier::class,
            [],
            Method::GET,
            '/ExternalApi/v2/ref/priceTier',
            [],
            null,
        ],
    ],
    'resources' => [
        'ref priceTier get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->priceTier()->get(),
            GetPriceTier::class,
            Method::GET,
            '/ExternalApi/v2/ref/priceTier',
            [],
            null,
        ],
    ],
    'dtos' => [
        GetPriceTier::class => [GetPriceTier::class, [], Cin7Payloads::load('ref/priceTier', 'get.response'), PriceTierData::class, 'PriceTiers'],
    ],
];
