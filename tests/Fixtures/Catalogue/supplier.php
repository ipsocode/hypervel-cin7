<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Supplier\SupplierData;
use Ipsocode\Cin7\Data\Supplier\SupplierPostData;
use Ipsocode\Cin7\Data\Supplier\SupplierPutData;
use Ipsocode\Cin7\Requests\Supplier\GetSupplier;
use Ipsocode\Cin7\Requests\Supplier\PostSupplier;
use Ipsocode\Cin7\Requests\Supplier\PutSupplier;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `supplier`; tests/Catalogue.php merges every file's rows by kind.

// The fields every supplier requires.
$fields = ['Name' => 'Bayside Club', 'Currency' => 'AUD', 'PaymentTerm' => '30 days', 'AccountPayable' => '800', 'TaxRule' => 'BAS Excluded'];

$supplier = ['Name' => 'Bayside Club', 'LastModifiedOn' => '2017-12-28T06:15:17.237Z'];
$supplierSent = ['Name' => 'Bayside Club'];

return [
    'requests' => [
        GetSupplier::class => [
            GetSupplier::class,
            ['id' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'name' => 'Bay', 'modifiedSince' => '2017-12-01T00:00:00', 'includeDeprecated' => true],
            Method::GET,
            '/ExternalApi/v2/supplier',
            ['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Name' => 'Bay', 'ModifiedSince' => '2017-12-01T00:00:00', 'IncludeDeprecated' => 'true', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostSupplier::class => [
            PostSupplier::class,
            [['Name' => 'Bayside Club']],
            Method::POST,
            '/ExternalApi/v2/supplier',
            [],
            ['Name' => 'Bayside Club'],
        ],
        PutSupplier::class => [
            PutSupplier::class,
            [['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Name' => 'Bayside Club']],
            Method::PUT,
            '/ExternalApi/v2/supplier',
            [],
            ['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Name' => 'Bayside Club'],
        ],
        PostSupplier::class . ' with data' => [
            PostSupplier::class,
            [fn (): SupplierPostData => SupplierPostData::from([...$fields, 'Status' => 'Active', 'AdditionalAttribute10' => 'x', 'Addresses' => [['Line1' => '148 Bay Harbour Road', 'Country' => 'Australia', 'Type' => 'Business']], 'Contacts' => [['Name' => 'Bob Partridge', 'Default' => true]]])],
            Method::POST,
            '/ExternalApi/v2/supplier',
            [],
            ['Status' => 'Active', 'Addresses' => [['Line1' => '148 Bay Harbour Road', 'Country' => 'Australia', 'Type' => 'Business']], 'Contacts' => [['Name' => 'Bob Partridge', 'Default' => true]], ...$fields, 'AdditionalAttribute10' => 'x'],
        ],
        PutSupplier::class . ' with data' => [
            PutSupplier::class,
            [fn (): SupplierPutData => SupplierPutData::from([...$fields, 'ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'TaxNumber' => null, 'Discount' => 0])],
            Method::PUT,
            '/ExternalApi/v2/supplier',
            [],
            ['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Discount' => 0, ...$fields],
        ],
    ],
    'resources' => [
        'supplier get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->get(),
            GetSupplier::class,
            Method::GET,
            '/ExternalApi/v2/supplier',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'supplier paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->paginate(name: 'Bay')->current(),
            GetSupplier::class,
            Method::GET,
            '/ExternalApi/v2/supplier',
            ['Name' => 'Bay', 'page' => 1, 'limit' => 100],
            null,
        ],
        'supplier post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->post(['Name' => 'Bayside Club']),
            PostSupplier::class,
            Method::POST,
            '/ExternalApi/v2/supplier',
            [],
            ['Name' => 'Bayside Club'],
        ],
        'supplier post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->post(SupplierPostData::from($fields)),
            PostSupplier::class,
            Method::POST,
            '/ExternalApi/v2/supplier',
            [],
            $fields,
        ],
        'supplier put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->put(['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Name' => 'Bayside Club']),
            PutSupplier::class,
            Method::PUT,
            '/ExternalApi/v2/supplier',
            [],
            ['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Name' => 'Bayside Club'],
        ],
        'supplier put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->supplier()->put(SupplierPutData::from([...$fields, 'ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Status' => 'Deprecated'])),
            PutSupplier::class,
            Method::PUT,
            '/ExternalApi/v2/supplier',
            [],
            ['ID' => '733062e8-56d7-4de6-ba06-ed8c690f5f01', 'Status' => 'Deprecated', ...$fields],
        ],
    ],
    'dtos' => [
        GetSupplier::class => [GetSupplier::class, [], Cin7Payloads::load('supplier', 'get.response'), SupplierData::class, 'SupplierList'],
        PostSupplier::class => [PostSupplier::class, [[]], Cin7Payloads::load('supplier', 'post.response'), SupplierData::class, 'SupplierList.0'],
        PutSupplier::class => [PutSupplier::class, [[]], Cin7Payloads::load('supplier', 'put.response'), SupplierData::class, 'SupplierList.0'],
    ],
    'bodies' => [
        SupplierPostData::class => [SupplierPostData::class, Cin7Payloads::load('supplier', 'post.request')],
        SupplierPutData::class => [SupplierPutData::class, Cin7Payloads::load('supplier', 'put.request')],
    ],
    'missing' => [
        'supplier POST without AccountPayable' => [SupplierPostData::class, Arr::except(Cin7Payloads::load('supplier', 'post.request'), 'AccountPayable')],
        'supplier PUT without ID' => [SupplierPutData::class, Arr::except(Cin7Payloads::load('supplier', 'put.request'), 'ID')],
        'supplier without ID' => [SupplierData::class, Arr::except(Cin7Payloads::load('supplier', 'get.response')['SupplierList'][0], 'ID')],
    ],
    'required' => [
        SupplierData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountPayable', 'TaxRule', 'ID'],
        SupplierPostData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountPayable', 'TaxRule'],
        SupplierPutData::class => ['Name', 'Currency', 'PaymentTerm', 'AccountPayable', 'TaxRule', 'ID'],
    ],
    'omitted' => [
        PostSupplier::class => [PostSupplier::class, $supplier, $supplierSent],
        PutSupplier::class => [PutSupplier::class, $supplier, $supplierSent],
    ],
];
