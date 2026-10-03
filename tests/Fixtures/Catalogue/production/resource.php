<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Resource\CustomWorkingDayData;
use Ipsocode\Cin7\Data\Production\Resource\ResourceAttachmentData;
use Ipsocode\Cin7\Data\Production\Resource\ResourceCostData;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcePostData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcePutData;
use Ipsocode\Cin7\Data\Production\Resource\ResourceRemarkData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcesData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcesPostData;
use Ipsocode\Cin7\Data\Production\Resource\ResourceUnitData;
use Ipsocode\Cin7\Requests\Production\Resource\DeleteProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\GetProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\PostProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\PutProductionResource;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/resource`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionResource::class => [
            GetProductionResource::class,
            [$id, 'includeAttachments' => true],
            Method::GET,
            '/ExternalApi/v2/production/resource',
            ['ResourceID' => $id, 'IncludeAttachments' => 'true'],
            null,
        ],
        PostProductionResource::class => [
            PostProductionResource::class,
            [['Resources' => [['Name' => 'Oven']]]],
            Method::POST,
            '/ExternalApi/v2/production/resource',
            [],
            ['Resources' => [['Name' => 'Oven']]],
        ],
        PutProductionResource::class => [
            PutProductionResource::class,
            [['ResourceID' => $id, 'Name' => 'Oven']],
            Method::PUT,
            '/ExternalApi/v2/production/resource',
            [],
            ['ResourceID' => $id, 'Name' => 'Oven'],
        ],
        DeleteProductionResource::class => [
            DeleteProductionResource::class,
            [$id],
            Method::DELETE,
            '/ExternalApi/v2/production/resource',
            ['ResourceID' => $id],
            null,
        ],
    ],
    'resources' => [
        'production resource get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->get($id, includeAttachments: true),
            GetProductionResource::class,
            Method::GET,
            '/ExternalApi/v2/production/resource',
            ['ResourceID' => $id, 'IncludeAttachments' => 'true'],
            null,
        ],
        'production resource post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->post(['Resources' => [['Name' => 'Oven']]]),
            PostProductionResource::class,
            Method::POST,
            '/ExternalApi/v2/production/resource',
            [],
            ['Resources' => [['Name' => 'Oven']]],
        ],
        'production resource put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->put(['ResourceID' => $id, 'Name' => 'Oven']),
            PutProductionResource::class,
            Method::PUT,
            '/ExternalApi/v2/production/resource',
            [],
            ['ResourceID' => $id, 'Name' => 'Oven'],
        ],
        'production resource delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->delete($id),
            DeleteProductionResource::class,
            Method::DELETE,
            '/ExternalApi/v2/production/resource',
            ['ResourceID' => $id],
            null,
        ],
    ],
    'dtos' => [
        GetProductionResource::class => [GetProductionResource::class, [$id], Cin7Payloads::load('production/resource', 'get.response'), ResourceData::class, ''],
        PostProductionResource::class => [PostProductionResource::class, [[]], Cin7Payloads::load('production/resource', 'post.response'), ResourcesData::class, ''],
        PutProductionResource::class => [PutProductionResource::class, [[]], Cin7Payloads::load('production/resource', 'put.response'), ResourceData::class, ''],
        DeleteProductionResource::class => [DeleteProductionResource::class, [$id], Cin7Payloads::load('production/resource', 'delete.response'), ResourceData::class, ''],
    ],
    'bodies' => [
        'ResourcesPostData production/resource' => [ResourcesPostData::class, Cin7Payloads::load('production/resource', 'post.request')],
        'ResourcePutData production/resource' => [ResourcePutData::class, Cin7Payloads::load('production/resource', 'put.request')],
    ],
    'missing' => [
        'CustomWorkingDayData without DayOfWeek' => [CustomWorkingDayData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceCapacities'][0]['CustomWorkingDays'][0], 'DayOfWeek')],
        'ResourceUnitData without Name' => [ResourceUnitData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceCapacities'][0]['ResourceUnits'][0], 'Name')],
        'ResourceCostData without ProductID' => [ResourceCostData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceCosts'][0], 'ProductID')],
        'ResourceCostData without AccountCode' => [ResourceCostData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceCosts'][0], 'AccountCode')],
        'ResourceCostData without PriceTier' => [ResourceCostData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceCosts'][0], 'PriceTier')],
        'ResourceRemarkData without Remark' => [ResourceRemarkData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceRemarks'][0], 'Remark')],
        'ResourceAttachmentData without FileName' => [ResourceAttachmentData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response')['ResourceAttachments'][0], 'FileName')],
        'ResourcePostData without Name' => [ResourcePostData::class, Arr::except(Cin7Payloads::load('production/resource', 'post.request')['Resources'][0], 'Name')],
        'ResourcePostData without ResourceType' => [ResourcePostData::class, Arr::except(Cin7Payloads::load('production/resource', 'post.request')['Resources'][0], 'ResourceType')],
        'ResourcePostData without CycleDuration' => [ResourcePostData::class, Arr::except(Cin7Payloads::load('production/resource', 'post.request')['Resources'][0], 'CycleDuration')],
        'ResourcesPostData without Resources' => [ResourcesPostData::class, Arr::except(Cin7Payloads::load('production/resource', 'post.request'), 'Resources')],
    ],
    'required' => [
        CustomWorkingDayData::class => ['DayOfWeek'],
        ResourceUnitData::class => ['Name'],
        ResourceCostData::class => ['ProductID', 'AccountCode', 'PriceTier'],
        ResourceRemarkData::class => ['Remark'],
        ResourceAttachmentData::class => ['FileName'],
        ResourcePostData::class => ['Name', 'ResourceType', 'CycleDuration'],
        ResourcesPostData::class => ['Resources'],
    ],
];
