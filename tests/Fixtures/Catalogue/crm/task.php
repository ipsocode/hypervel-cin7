<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Task\TaskData;
use Ipsocode\Cin7\Data\Crm\Task\TaskPostData;
use Ipsocode\Cin7\Data\Crm\Task\TaskPutData;
use Ipsocode\Cin7\Requests\Crm\Task\GetCrmTask;
use Ipsocode\Cin7\Requests\Crm\Task\PostCrmTask;
use Ipsocode\Cin7\Requests\Crm\Task\PutCrmTask;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `crm/task`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/crm/task';

return [
    'requests' => [
        GetCrmTask::class => [GetCrmTask::class, ['id' => $id, 'name' => 'Sale', 'startDateFrom' => '2022-05-01T00:00:00', 'startDateTo' => '2022-05-31T00:00:00', 'endDateFrom' => '2022-05-02T00:00:00', 'endDateTo' => '2022-05-30T00:00:00', 'completeDateFrom' => '2022-05-03T00:00:00', 'completeDateTo' => '2022-05-29T00:00:00', 'assignedTo' => 'a@b.test', 'category' => 'task cat 1'], Method::GET, $uri, ['ID' => $id, 'Name' => 'Sale', 'StartDateFrom' => '2022-05-01T00:00:00', 'StartDateTo' => '2022-05-31T00:00:00', 'EndDateFrom' => '2022-05-02T00:00:00', 'EndDateTo' => '2022-05-30T00:00:00', 'CompleteDateFrom' => '2022-05-03T00:00:00', 'CompleteDateTo' => '2022-05-29T00:00:00', 'AssignedTo' => 'a@b.test', 'Category' => 'task cat 1', 'page' => 1, 'limit' => 100], null],
        PostCrmTask::class => [PostCrmTask::class, [['Name' => 'A']], Method::POST, $uri, [], ['Name' => 'A']],
        PutCrmTask::class => [PutCrmTask::class, [['ID' => $id, 'Name' => 'B']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'resources' => [
        'crm task get' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->task()->get(), GetCrmTask::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'crm task paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->task()->paginate(category: 'task cat 1')->current(), GetCrmTask::class, Method::GET, $uri, ['Category' => 'task cat 1', 'page' => 1, 'limit' => 100], null],
        'crm task post' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->task()->post(['Name' => 'A']), PostCrmTask::class, Method::POST, $uri, [], ['Name' => 'A']],
        'crm task put' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->task()->put(['ID' => $id, 'Name' => 'B']), PutCrmTask::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'dtos' => [
        GetCrmTask::class => [GetCrmTask::class, [], Cin7Payloads::load('crm/task', 'get.response'), TaskData::class, 'Tasks'],
        PostCrmTask::class => [PostCrmTask::class, [[]], Cin7Payloads::load('crm/task', 'post.response'), TaskData::class, 'Tasks'],
        PutCrmTask::class => [PutCrmTask::class, [[]], Cin7Payloads::load('crm/task', 'put.response'), TaskData::class, 'Tasks'],
    ],
    'bodies' => [
        TaskPostData::class => [TaskPostData::class, Cin7Payloads::load('crm/task', 'post.request')],
        TaskPutData::class => [TaskPutData::class, Cin7Payloads::load('crm/task', 'put.request')],
    ],
    'missing' => [
        'Task POST without Name' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'Name')],
        'Task POST without StartDate' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'StartDate')],
        'Task POST without EndDate' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'EndDate')],
        'Task POST without EntityType' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'EntityType')],
        'Task POST without EntityID' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'EntityID')],
        'Task POST without TaskStatus' => [TaskPostData::class, Arr::except(Cin7Payloads::load('crm/task', 'post.request'), 'TaskStatus')],
        'Task PUT without Name' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'Name')],
        'Task PUT without StartDate' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'StartDate')],
        'Task PUT without EndDate' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'EndDate')],
        'Task PUT without EntityType' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'EntityType')],
        'Task PUT without EntityID' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'EntityID')],
        'Task PUT without TaskStatus' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'TaskStatus')],
        'Task PUT without ID' => [TaskPutData::class, Arr::except(Cin7Payloads::load('crm/task', 'put.request'), 'ID')],
        'Task without Name' => [TaskData::class, Arr::except(Cin7Payloads::load('crm/task', 'get.response')['Tasks'][0], 'Name')],
    ],
    'required' => [
        TaskData::class => ['Name', 'StartDate', 'EndDate', 'EntityType', 'EntityID', 'TaskStatus'],
        TaskPostData::class => ['Name', 'StartDate', 'EndDate', 'EntityType', 'EntityID', 'TaskStatus'],
        TaskPutData::class => ['Name', 'StartDate', 'EndDate', 'EntityType', 'EntityID', 'TaskStatus', 'ID'],
    ],
];
