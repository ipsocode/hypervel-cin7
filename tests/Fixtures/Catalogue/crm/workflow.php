<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowData;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowPostData;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowPutData;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowStepData;
use Ipsocode\Cin7\Requests\Crm\Workflow\GetCrmWorkflow;
use Ipsocode\Cin7\Requests\Crm\Workflow\PostCrmWorkflow;
use Ipsocode\Cin7\Requests\Crm\Workflow\PutCrmWorkflow;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `crm/workflow`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/crm/workflow';

return [
    'requests' => [
        GetCrmWorkflow::class => [GetCrmWorkflow::class, ['id' => $id, 'name' => 'my'], Method::GET, $uri, ['ID' => $id, 'Name' => 'my', 'page' => 1, 'limit' => 100], null],
        PostCrmWorkflow::class => [PostCrmWorkflow::class, [['Name' => 'A']], Method::POST, $uri, [], ['Name' => 'A']],
        PostCrmWorkflow::class . ' with data' => [
            PostCrmWorkflow::class,
            [fn (): WorkflowPostData => WorkflowPostData::from(Cin7Payloads::load('crm/workflow', 'post.request'))],
            Method::POST,
            $uri,
            [],
            [
                'IsActive' => true,
                'Steps' => [
                    [
                        'Name' => 'Task 1',
                        'SkipHoliday' => 'move_previous',
                        'Days' => 0,
                        'StartTime' => '03:00:00',
                        'EndTime' => '06:00:00',
                        'UserName' => 'niknav@yandex.ru',
                    ],
                    [
                        'Name' => 'Task 2',
                        'SkipHoliday' => 'leave',
                        'Category' => 'API test category',
                        'Days' => 0,
                    ],
                ],
                'Name' => 'my task workflow 2',
                'EntityType' => 'Sale',
                'DueDaysType' => 'COMPLETED_DATE',
            ],
        ],
        PutCrmWorkflow::class => [PutCrmWorkflow::class, [['ID' => $id, 'Name' => 'B']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
        PutCrmWorkflow::class . ' with data' => [
            PutCrmWorkflow::class,
            [fn (): WorkflowPutData => WorkflowPutData::from(Cin7Payloads::load('crm/workflow', 'put.request'))],
            Method::PUT,
            $uri,
            [],
            [
                'ID' => '66364c4f-0480-4db0-8848-d224fffd543a',
                'IsActive' => true,
                'Steps' => [
                    [
                        'Name' => 'Task 1',
                        'SkipHoliday' => 'move_previous',
                        'Days' => 0,
                        'StartTime' => '03:00:00',
                        'EndTime' => '06:00:00',
                        'UserName' => 'niknav@yandex.ru',
                    ],
                    [
                        'Name' => 'Task 2',
                        'SkipHoliday' => 'leave',
                        'Category' => 'API test category',
                        'Days' => 0,
                        'StartTime' => '07:00:00',
                        'EndTime' => '10:00:00',
                        'UserName' => 'niknav@yandex.ru',
                    ],
                ],
                'Name' => 'my task workflow 3',
                'EntityType' => 'Sale',
                'DueDaysType' => 'COMPLETED_DATE',
            ],
        ],
    ],
    'resources' => [
        'crm workflow get' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->workflow()->get(), GetCrmWorkflow::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'crm workflow paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->workflow()->paginate(name: 'my')->current(), GetCrmWorkflow::class, Method::GET, $uri, ['Name' => 'my', 'page' => 1, 'limit' => 100], null],
        'crm workflow post' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->workflow()->post(['Name' => 'A']), PostCrmWorkflow::class],
        'crm workflow put' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->workflow()->put(['ID' => $id, 'Name' => 'B']), PutCrmWorkflow::class],
    ],
    'dtos' => [
        GetCrmWorkflow::class => [GetCrmWorkflow::class, [], Cin7Payloads::load('crm/workflow', 'get.response'), WorkflowData::class, 'Workflows'],
        PostCrmWorkflow::class => [PostCrmWorkflow::class, [[]], Cin7Payloads::load('crm/workflow', 'post.response'), WorkflowData::class, 'Workflows'],
        PutCrmWorkflow::class => [PutCrmWorkflow::class, [[]], Cin7Payloads::load('crm/workflow', 'put.response'), WorkflowData::class, 'Workflows'],
    ],
    'bodies' => [
        WorkflowPostData::class => [WorkflowPostData::class, PostCrmWorkflow::class, Cin7Payloads::load('crm/workflow', 'post.request')],
        WorkflowPutData::class => [WorkflowPutData::class, PutCrmWorkflow::class, Cin7Payloads::load('crm/workflow', 'put.request')],
    ],
    'missing' => [
        'Workflow without ID' => [WorkflowData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'get.response')['Workflows'][0], 'ID')],
        'Workflow POST without Name' => [WorkflowPostData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'post.request'), 'Name')],
        'Workflow POST without EntityType' => [WorkflowPostData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'post.request'), 'EntityType')],
        'Workflow POST without DueDaysType' => [WorkflowPostData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'post.request'), 'DueDaysType')],
        'Workflow PUT without Name' => [WorkflowPutData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'put.request'), 'Name')],
        'Workflow PUT without EntityType' => [WorkflowPutData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'put.request'), 'EntityType')],
        'Workflow PUT without DueDaysType' => [WorkflowPutData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'put.request'), 'DueDaysType')],
        'Workflow PUT without ID' => [WorkflowPutData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'put.request'), 'ID')],
        'Workflow without Name' => [WorkflowData::class, Arr::except(Cin7Payloads::load('crm/workflow', 'get.response')['Workflows'][0], 'Name')],
    ],
    'required' => [
        WorkflowData::class => ['Name', 'EntityType', 'DueDaysType', 'ID'],
        WorkflowPostData::class => ['Name', 'EntityType', 'DueDaysType'],
        WorkflowPutData::class => ['Name', 'EntityType', 'DueDaysType', 'ID'],
        WorkflowStepData::class => ['Name', 'SkipHoliday'],
    ],
];
