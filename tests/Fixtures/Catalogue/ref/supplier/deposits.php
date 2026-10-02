<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Supplier\Deposits\SupplierDepositData;
use Ipsocode\Cin7\Requests\Ref\Supplier\Deposits\GetSupplierDeposits;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/supplier/deposits`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSupplierDeposits::class => [
            GetSupplierDeposits::class,
            [],
            Method::GET,
            '/ExternalApi/v2/ref/supplier/deposits',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'ref supplier deposits get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->supplier()->deposits()->get(supplierId: 'ce607b9a-dc9b-4ef4-9ed3-495434e90467', showUsedDeposits: true),
            GetSupplierDeposits::class,
            Method::GET,
            '/ExternalApi/v2/ref/supplier/deposits',
            ['SupplierID' => 'ce607b9a-dc9b-4ef4-9ed3-495434e90467', 'ShowUsedDeposits' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref supplier deposits paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->supplier()->deposits()->paginate()->current(),
            GetSupplierDeposits::class,
            Method::GET,
            '/ExternalApi/v2/ref/supplier/deposits',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetSupplierDeposits::class => [
            GetSupplierDeposits::class,
            [],
            Cin7Payloads::load('ref/supplier/deposits', 'get.response'),
            SupplierDepositData::class,
            'SupplierDeposits',
        ],
    ],
];
