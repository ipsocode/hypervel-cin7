<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryData;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryPostData;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryPutData;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\GetCrmTaskCategory;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\PostCrmTaskCategory;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\PutCrmTaskCategory;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `crm/taskcategory`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/crm/taskcategory';

return [
    'requests' => [
        GetCrmTaskCategory::class => [GetCrmTaskCategory::class, ['id' => $id, 'name' => 'task'], Method::GET, $uri, ['ID' => $id, 'Name' => 'task', 'page' => 1, 'limit' => 100], null],
        PostCrmTaskCategory::class => [PostCrmTaskCategory::class, [['Name' => 'A']], Method::POST, $uri, [], ['Name' => 'A']],
        PutCrmTaskCategory::class => [PutCrmTaskCategory::class, [['ID' => $id, 'Name' => 'B']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'resources' => [
        'crm taskCategory get' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->taskCategory()->get(), GetCrmTaskCategory::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'crm taskCategory paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->taskCategory()->paginate(name: 'task')->current(), GetCrmTaskCategory::class, Method::GET, $uri, ['Name' => 'task', 'page' => 1, 'limit' => 100], null],
        'crm taskCategory post' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->taskCategory()->post(['Name' => 'A']), PostCrmTaskCategory::class, Method::POST, $uri, [], ['Name' => 'A']],
        'crm taskCategory put' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->taskCategory()->put(['ID' => $id, 'Name' => 'B']), PutCrmTaskCategory::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'dtos' => [
        GetCrmTaskCategory::class => [GetCrmTaskCategory::class, [], Cin7Payloads::load('crm/taskcategory', 'get.response'), TaskCategoryData::class, 'Categories'],
        PostCrmTaskCategory::class => [PostCrmTaskCategory::class, [[]], Cin7Payloads::load('crm/taskcategory', 'post.response'), TaskCategoryData::class, 'Categories'],
        PutCrmTaskCategory::class => [PutCrmTaskCategory::class, [[]], Cin7Payloads::load('crm/taskcategory', 'put.response'), TaskCategoryData::class, 'Categories'],
    ],
    'bodies' => [
        TaskCategoryPostData::class => [TaskCategoryPostData::class, Cin7Payloads::load('crm/taskcategory', 'post.request')],
        TaskCategoryPutData::class => [TaskCategoryPutData::class, Cin7Payloads::load('crm/taskcategory', 'put.request')],
    ],
    'missing' => [
        'Task category without ID' => [TaskCategoryData::class, Arr::except(Cin7Payloads::load('crm/taskcategory', 'get.response')['Categories'][0], 'ID')],
        'TaskCategory POST without Name' => [TaskCategoryPostData::class, Arr::except(Cin7Payloads::load('crm/taskcategory', 'post.request'), 'Name')],
        'TaskCategory PUT without Name' => [TaskCategoryPutData::class, Arr::except(Cin7Payloads::load('crm/taskcategory', 'put.request'), 'Name')],
        'TaskCategory PUT without ID' => [TaskCategoryPutData::class, Arr::except(Cin7Payloads::load('crm/taskcategory', 'put.request'), 'ID')],
        'TaskCategory without Name' => [TaskCategoryData::class, Arr::except(Cin7Payloads::load('crm/taskcategory', 'get.response')['Categories'][0], 'Name')],
    ],
    'required' => [
        TaskCategoryData::class => ['Name', 'ID'],
        TaskCategoryPostData::class => ['Name'],
        TaskCategoryPutData::class => ['Name', 'ID'],
    ],
];
