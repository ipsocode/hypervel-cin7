<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedSale\AdvancedSalePostData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;

// The catalogue rows for `advancedSale`, which the reference serves through `sale`: it has no
// requests of its own, so only the resource and its body class have rows.
// tests/Catalogue.php merges every file's rows by kind.

return [
    'resources' => [
        'advancedSale get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->get('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', includeTransactions: true),
            GetSale::class,
            Method::GET,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'IncludeTransactions' => 'true'],
            null,
        ],
        'advancedSale post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->post(['Customer' => 'ACME', 'SaleType' => 'Advanced']),
            PostSale::class,
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME', 'SaleType' => 'Advanced'],
        ],
        'advancedSale post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->post(AdvancedSalePostData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])),
            PostSale::class,
            Method::POST,
            '/ExternalApi/v2/sale',
            [],
            ['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0, 'SaleType' => 'Advanced'],
        ],
        'advancedSale put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->put(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush']),
            PutSale::class,
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Note' => 'Rush'],
        ],
        'advancedSale put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->put(SalePutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Note' => 'Rush', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])),
            PutSale::class,
            Method::PUT,
            '/ExternalApi/v2/sale',
            [],
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Note' => 'Rush', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0],
        ],
        'advancedSale delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedSale()->delete('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', void: true),
            DeleteSale::class,
            Method::DELETE,
            '/ExternalApi/v2/sale',
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Void' => 'true'],
            null,
        ],
    ],
    'required' => [
        AdvancedSalePostData::class => ['Location', 'CurrencyRate'],
    ],
];
