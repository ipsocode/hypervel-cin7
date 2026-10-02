<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\DeleteSaleFulfilment;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\GetSaleFulfilment;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\PostSaleFulfilment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/fulfilment`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSaleFulfilment::class => [
            GetSaleFulfilment::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4', 'includeProductInfo' => true],
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'IncludeProductInfo' => 'true'],
            null,
        ],
        PostSaleFulfilment::class => [
            PostSaleFulfilment::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        PostSaleFulfilment::class . ' with data' => [
            PostSaleFulfilment::class,
            [fn (): SaleFulfilmentsData => SaleFulfilmentsData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'])],
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        DeleteSaleFulfilment::class => [
            DeleteSaleFulfilment::class,
            ['cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/sale/fulfilment',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Void' => 'true'],
            null,
        ],
    ],
    'resources' => [
        'sale fulfilment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
            GetSaleFulfilment::class,
            Method::GET,
            '/ExternalApi/v2/sale/fulfilment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        'sale fulfilment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']),
            PostSaleFulfilment::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale fulfilment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->post(SaleFulfilmentsData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'])),
            PostSaleFulfilment::class,
            Method::POST,
            '/ExternalApi/v2/sale/fulfilment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
        'sale fulfilment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->delete('cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'),
            DeleteSaleFulfilment::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/fulfilment',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57'],
            null,
        ],
        'sale fulfilment delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->fulfilment()->delete('cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', void: true),
            DeleteSaleFulfilment::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/fulfilment',
            ['TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57', 'Void' => 'true'],
            null,
        ],
    ],
    'dtos' => [
        GetSaleFulfilment::class => [GetSaleFulfilment::class, ['sale-1'], Cin7Payloads::load('sale/fulfilment', 'get.response'), SaleFulfilmentsData::class, ''],
        PostSaleFulfilment::class => [PostSaleFulfilment::class, [[]], Cin7Payloads::load('sale/fulfilment', 'get.response'), SaleFulfilmentsData::class, ''],
        DeleteSaleFulfilment::class => [DeleteSaleFulfilment::class, ['task-1'], Cin7Payloads::load('sale/fulfilment', 'get.response'), SaleFulfilmentsData::class, ''],
    ],
    'bodies' => [
        'fulfilment POST' => [SaleFulfilmentsData::class, Cin7Payloads::load('sale/fulfilment', 'post.request')],
    ],
    'missing' => [
        'fulfilments without SaleID' => [SaleFulfilmentsData::class, Arr::except(Cin7Payloads::load('sale/fulfilment', 'get.response'), 'SaleID')],
    ],
    'required' => [
        SaleFulfilmentsData::class => ['SaleID'],
    ],
];
