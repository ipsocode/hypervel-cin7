<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Templates\TemplateData;
use Ipsocode\Cin7\Requests\Ref\Templates\GetTemplates;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/templates`; tests/Catalogue.php merges every file's rows by kind.

$uri = '/ExternalApi/v2/ref/templates';

return [
    'requests' => [
        GetTemplates::class => [GetTemplates::class, ['type' => 'Purchase Order', 'name' => 'Purchase order'], Method::GET, $uri, ['Type' => 'Purchase Order', 'Name' => 'Purchase order', 'page' => 1, 'limit' => 100], null],
    ],
    'resources' => [
        'ref templates get' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->templates()->get(type: 'Opportunity'), GetTemplates::class, Method::GET, $uri, ['Type' => 'Opportunity', 'page' => 1, 'limit' => 100], null],
        'ref templates paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->templates()->paginate(name: 'A')->current(), GetTemplates::class, Method::GET, $uri, ['Name' => 'A', 'page' => 1, 'limit' => 100], null],
    ],
    'dtos' => [
        GetTemplates::class => [GetTemplates::class, [], Cin7Payloads::load('ref/templates', 'get.response'), TemplateData::class, 'Templates'],
    ],
];
