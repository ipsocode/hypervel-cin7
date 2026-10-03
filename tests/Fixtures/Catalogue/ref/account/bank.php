<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Account\Bank\BankAccountData;
use Ipsocode\Cin7\Requests\Ref\Account\Bank\GetAccountBank;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/account/bank`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetAccountBank::class => [
            GetAccountBank::class,
            ['id' => 'd5b0294d-e931-47d4-a58c-5c9e2d7d3090', 'name' => 'EFT', 'bank' => 'Unknown Bank'],
            Method::GET,
            '/ExternalApi/v2/ref/account/bank',
            ['ID' => 'd5b0294d-e931-47d4-a58c-5c9e2d7d3090', 'Name' => 'EFT', 'Bank' => 'Unknown Bank', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'ref account bank get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->bank()->get(),
            GetAccountBank::class,
            Method::GET,
            '/ExternalApi/v2/ref/account/bank',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref account bank paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->bank()->paginate(bank: 'Unknown Bank')->current(),
            GetAccountBank::class,
            Method::GET,
            '/ExternalApi/v2/ref/account/bank',
            ['Bank' => 'Unknown Bank', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetAccountBank::class => [GetAccountBank::class, [], Cin7Payloads::load('ref/account/bank', 'get.response'), BankAccountData::class, 'BankAccountsList'],
    ],
];
