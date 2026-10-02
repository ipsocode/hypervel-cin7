<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Requests\Crm\WorkflowStart\PostCrmWorkflowStart;

// The catalogue rows for `crm/workflowstart`; tests/Catalogue.php merges every file's rows by kind.

$id = '66364c4f-0480-4db0-8848-d224fffd543a';
$entity = 'f0b0be7b-0c9b-441c-8c0c-fe0b40697f3f';
$uri = '/ExternalApi/v2/crm/workflowstart';

return [
    'requests' => [
        PostCrmWorkflowStart::class => [
            PostCrmWorkflowStart::class,
            ['2022-05-04T13:40:00', 'Sale', $entity, $id, 'my task workflow'],
            Method::POST,
            $uri,
            ['ID' => $id, 'Name' => 'my task workflow', 'StartDate' => '2022-05-04T13:40:00', 'EnityType' => 'Sale', 'EntityID' => $entity],
            null,
        ],
    ],
    'resources' => [
        'crm workflowStart post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->crm()->workflowStart()->post('2022-05-04T13:40:00', TaskEntityType::Opportunity, $entity, name: 'my task workflow'),
            PostCrmWorkflowStart::class,
            Method::POST,
            $uri,
            ['Name' => 'my task workflow', 'StartDate' => '2022-05-04T13:40:00', 'EnityType' => 'Opportunity', 'EntityID' => $entity],
            null,
        ],
    ],
];
