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

// The catalog rows for `production/resource`; tests/Catalogue.php merges every file's rows by kind.

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
        PostProductionResource::class . ' with data' => [
            PostProductionResource::class,
            [fn (): ResourcesPostData => ResourcesPostData::from(Cin7Payloads::load('production/resource', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/production/resource',
            [],
            [
                'Resources' => [
                    [
                        'Name' => 'Chef',
                        'Code' => 'R-1',
                        'Tags' => '',
                        'IsActive' => true,
                        'IsInfinite' => false,
                        'IsAllowAllCapacityUsage' => false,
                        'IsAvailableOnHolidays' => false,
                        'IsAvailableOnWeekends' => false,
                        'ResourceCapacities' => [
                            [
                                'LocationID' => '489bfe24-4aec-49b9-916d-5e5466d47290',
                                'CustomWorkingDays' => [
                                    [
                                        'DayOfWeek' => 'Sunday',
                                        'StartTime' => 435,
                                        'EndTime' => 840,
                                        'BreakStartTime' => 0,
                                        'BreakEndTime' => 0,
                                    ],
                                    [
                                        'DayOfWeek' => 'Saturday',
                                        'StartTime' => 435,
                                        'EndTime' => 840,
                                        'BreakStartTime' => 0,
                                        'BreakEndTime' => 0,
                                    ],
                                    [
                                        'DayOfWeek' => 'Monday',
                                        'StartTime' => 435,
                                        'EndTime' => 840,
                                        'BreakStartTime' => 0,
                                        'BreakEndTime' => 0,
                                        'Capacity' => 0,
                                    ],
                                    [
                                        'DayOfWeek' => 'Tuesday',
                                        'StartTime' => 435,
                                        'EndTime' => 840,
                                        'BreakStartTime' => 0,
                                        'BreakEndTime' => 0,
                                    ],
                                ],
                                'ResourceUnits' => [
                                    [
                                        'Name' => 'R-1 - 9',
                                    ],
                                    [
                                        'Name' => 'R-1 - 8',
                                    ],
                                ],
                            ],
                            [
                                'LocationID' => '0056e0df-8d4c-4daf-9709-cbfb1b398482',
                                'CustomWorkingDays' => [],
                                'ResourceUnits' => [],
                            ],
                        ],
                        'ResourceCosts' => [
                            [
                                'ProductID' => '0fdbfec3-ab42-4d9a-aa51-45e1f2138d15',
                                'AccountCode' => '5300',
                                'PriceTier' => 1,
                            ],
                            [
                                'ProductID' => 'ac9629fa-8e01-439b-a0bf-938136f0065d',
                                'AccountCode' => '5100',
                                'PriceTier' => 1,
                            ],
                        ],
                        'ResourceRemarks' => [
                            [
                                'Remark' => 'hello, it\'s the first remark',
                            ],
                        ],
                        'ResourceAttachments' => [
                            [
                                'FileName' => 'FileName.docx',
                                'Content' => 'string Base64',
                            ],
                        ],
                        'ResourceType' => 'Machine',
                        'CycleDuration' => 2040,
                    ],
                ],
            ],
        ],
        PutProductionResource::class => [
            PutProductionResource::class,
            [['ResourceID' => $id, 'Name' => 'Oven']],
            Method::PUT,
            '/ExternalApi/v2/production/resource',
            [],
            ['ResourceID' => $id, 'Name' => 'Oven'],
        ],
        PutProductionResource::class . ' with data' => [
            PutProductionResource::class,
            [fn (): ResourcePutData => ResourcePutData::from(Cin7Payloads::load('production/resource', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/resource',
            [],
            [
                'Code' => 'R-1',
                'Name' => 'Chef',
                'Tags' => '',
                'IsActive' => true,
                'IsInfinite' => false,
                'IsAllowAllCapacityUsage' => false,
                'IsAvailableOnHolidays' => false,
                'IsAvailableOnWeekends' => false,
                'ResourceCapacities' => [],
                'ResourceCosts' => [],
                'ResourceRemarks' => [],
                'ResourceAttachments' => [],
                'ResourceType' => 'Machine',
                'CycleDuration' => 2040,
            ],
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
        ],
        'production resource post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->post(['Resources' => [['Name' => 'Oven']]]),
            PostProductionResource::class,
        ],
        'production resource put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->put(['ResourceID' => $id, 'Name' => 'Oven']),
            PutProductionResource::class,
        ],
        'production resource delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->resource()->delete($id),
            DeleteProductionResource::class,
        ],
    ],
    'dtos' => [
        GetProductionResource::class => [GetProductionResource::class, [$id], Cin7Payloads::load('production/resource', 'get.response'), ResourceData::class, ''],
        PostProductionResource::class => [PostProductionResource::class, [[]], Cin7Payloads::load('production/resource', 'post.response'), ResourcesData::class, ''],
        PutProductionResource::class => [PutProductionResource::class, [[]], Cin7Payloads::load('production/resource', 'put.response'), ResourceData::class, ''],
        DeleteProductionResource::class => [DeleteProductionResource::class, [$id], Cin7Payloads::load('production/resource', 'delete.response'), ResourceData::class, ''],
    ],
    'bodies' => [
        'ResourcesPostData production/resource' => [ResourcesPostData::class, PostProductionResource::class, Cin7Payloads::load('production/resource', 'post.request')],
        'ResourcePutData production/resource' => [ResourcePutData::class, PutProductionResource::class, Cin7Payloads::load('production/resource', 'put.request')],
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
        'ResourceData without ResourceType' => [ResourceData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response'), 'ResourceType')],
        'ResourceData without CycleDuration' => [ResourceData::class, Arr::except(Cin7Payloads::load('production/resource', 'get.response'), 'CycleDuration')],
        'ResourcePutData without ResourceType' => [ResourcePutData::class, Arr::except(Cin7Payloads::load('production/resource', 'put.request'), 'ResourceType')],
        'ResourcePutData without CycleDuration' => [ResourcePutData::class, Arr::except(Cin7Payloads::load('production/resource', 'put.request'), 'CycleDuration')],
        'ResourcesPostData without Resources' => [ResourcesPostData::class, Arr::except(Cin7Payloads::load('production/resource', 'post.request'), 'Resources')],
    ],
    'required' => [
        CustomWorkingDayData::class => ['DayOfWeek'],
        ResourceUnitData::class => ['Name'],
        ResourceCostData::class => ['ProductID', 'AccountCode', 'PriceTier'],
        ResourceRemarkData::class => ['Remark'],
        ResourceAttachmentData::class => ['FileName'],
        ResourceData::class => ['ResourceType', 'CycleDuration'],
        ResourcePostData::class => ['ResourceType', 'CycleDuration', 'Name'],
        ResourcePutData::class => ['ResourceType', 'CycleDuration'],
        ResourcesPostData::class => ['Resources'],
    ],
];
