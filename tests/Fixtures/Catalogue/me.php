<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Me\MeData;
use Ipsocode\Cin7\Requests\Me\GetMe;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `me`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetMe::class => [
            GetMe::class,
            [],
            Method::GET,
            '/ExternalApi/v2/me',
            [],
            null,
        ],
    ],
    'resources' => [
        'me get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->get(),
            GetMe::class,
            Method::GET,
            '/ExternalApi/v2/me',
            [],
            null,
        ],
    ],
    'dtos' => [
        GetMe::class => [GetMe::class, [], Cin7Payloads::load('me', 'get.response'), MeData::class, ''],
    ],
];
