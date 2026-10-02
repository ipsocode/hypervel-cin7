<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Transactions\TransactionData;
use Ipsocode\Cin7\Requests\Transactions\GetTransactions;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `transactions`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetTransactions::class => [
            GetTransactions::class,
            ['fromDate' => '2017-12-01T00:00:00', 'toDate' => '2017-12-31T00:00:00', 'account' => '610'],
            Method::GET,
            '/ExternalApi/v2/transactions',
            ['FromDate' => '2017-12-01T00:00:00', 'ToDate' => '2017-12-31T00:00:00', 'Account' => '610', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'transactions get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->transactions()->get(account: '610'),
            GetTransactions::class,
            Method::GET,
            '/ExternalApi/v2/transactions',
            ['Account' => '610', 'page' => 1, 'limit' => 100],
            null,
        ],
        'transactions paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->transactions()->paginate(fromDate: '2017-12-01T00:00:00')->current(),
            GetTransactions::class,
            Method::GET,
            '/ExternalApi/v2/transactions',
            ['FromDate' => '2017-12-01T00:00:00', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetTransactions::class => [GetTransactions::class, [], Cin7Payloads::load('transactions', 'get.response'), TransactionData::class, 'Transactions'],
    ],
];
