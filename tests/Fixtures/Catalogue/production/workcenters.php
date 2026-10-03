<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCenterData;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCenterLocationData;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCentersData;
use Ipsocode\Cin7\Requests\Production\WorkCenters\DeleteProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\GetProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\PostProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\PutProductionWorkCenters;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/workcenters`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionWorkCenters::class => [
            GetProductionWorkCenters::class,
            ['name' => 'x'],
            Method::GET,
            '/ExternalApi/v2/production/workcenters',
            ['Name' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostProductionWorkCenters::class => [
            PostProductionWorkCenters::class,
            [['Workcenters' => []]],
            Method::POST,
            '/ExternalApi/v2/production/workcenters',
            [],
            ['Workcenters' => []],
        ],
        PutProductionWorkCenters::class => [
            PutProductionWorkCenters::class,
            [['Workcenters' => []]],
            Method::PUT,
            '/ExternalApi/v2/production/workcenters',
            [],
            ['Workcenters' => []],
        ],
        DeleteProductionWorkCenters::class => [
            DeleteProductionWorkCenters::class,
            [$id],
            Method::DELETE,
            '/ExternalApi/v2/production/workcenters',
            ['WorkCenterId' => $id],
            null,
        ],
    ],
    'resources' => [
        'production workCenters get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->workCenters()->get(name: 'x'),
            GetProductionWorkCenters::class,
            Method::GET,
            '/ExternalApi/v2/production/workcenters',
            ['Name' => 'x', 'page' => 1, 'limit' => 100],
            null,
        ],
        'production workCenters paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->workCenters()->paginate()->current(),
            GetProductionWorkCenters::class,
            Method::GET,
            '/ExternalApi/v2/production/workcenters',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'production workCenters post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->workCenters()->post(['Workcenters' => []]),
            PostProductionWorkCenters::class,
            Method::POST,
            '/ExternalApi/v2/production/workcenters',
            [],
            ['Workcenters' => []],
        ],
        'production workCenters put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->workCenters()->put(['Workcenters' => []]),
            PutProductionWorkCenters::class,
            Method::PUT,
            '/ExternalApi/v2/production/workcenters',
            [],
            ['Workcenters' => []],
        ],
        'production workCenters delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->workCenters()->delete($id),
            DeleteProductionWorkCenters::class,
            Method::DELETE,
            '/ExternalApi/v2/production/workcenters',
            ['WorkCenterId' => $id],
            null,
        ],
    ],
    'dtos' => [
        GetProductionWorkCenters::class => [GetProductionWorkCenters::class, [], Cin7Payloads::load('production/workcenters', 'get.response'), WorkCenterData::class, 'Workcenters'],
        PostProductionWorkCenters::class => [PostProductionWorkCenters::class, [[]], Cin7Payloads::load('production/workcenters', 'post.response'), WorkCentersData::class, ''],
        PutProductionWorkCenters::class => [PutProductionWorkCenters::class, [[]], Cin7Payloads::load('production/workcenters', 'put.response'), WorkCentersData::class, ''],
    ],
    'bodies' => [
        'WorkCentersData production/workcenters' => [WorkCentersData::class, Cin7Payloads::load('production/workcenters', 'post.request')],
        'WorkCentersData production/workcenters' => [WorkCentersData::class, Cin7Payloads::load('production/workcenters', 'put.request')],
    ],
    'missing' => [
        'WorkCenterLocationData without ID' => [WorkCenterLocationData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0]['WorkCenterLocations'][0], 'ID')],
        'WorkCenterLocationData without LocationID' => [WorkCenterLocationData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0]['WorkCenterLocations'][0], 'LocationID')],
        'WorkCenterLocationData without Type' => [WorkCenterLocationData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0]['WorkCenterLocations'][0], 'Type')],
        'WorkCenterLocationData without LocationName' => [WorkCenterLocationData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0]['WorkCenterLocations'][0], 'LocationName')],
        'WorkCenterData without WorkCenterID' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'WorkCenterID')],
        'WorkCenterData without Code' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'Code')],
        'WorkCenterData without Name' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'Name')],
        'WorkCenterData without IsActive' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'IsActive')],
        'WorkCenterData without IsCoMan' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'IsCoMan')],
        'WorkCenterData without IsCoManPurchase' => [WorkCenterData::class, Arr::except(Cin7Payloads::load('production/workcenters', 'post.request')['Workcenters'][0], 'IsCoManPurchase')],
    ],
    'required' => [
        WorkCenterLocationData::class => ['ID', 'LocationID', 'Type', 'LocationName'],
        WorkCenterData::class => ['WorkCenterID', 'Code', 'Name', 'IsActive', 'IsCoMan', 'IsCoManPurchase'],
    ],
];
