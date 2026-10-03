<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplateData;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplatesPostData;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\DeleteCustomerTemplates;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\GetCustomerTemplates;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\PostCustomerTemplates;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/customer/templates`; tests/Catalogue.php merges every file's rows by kind.

$customer = 'd7392f64-4d48-424c-8dfa-36f8895162ff';
$template = '344eaab7-c7f5-4b1e-a7b6-d29ebf6cf7b6';
$body = ['CustomerTemplates' => [['CustomerID' => $customer, 'TemplateID' => $template]]];
$uri = '/ExternalApi/v2/ref/customer/templates';

return [
    'requests' => [
        GetCustomerTemplates::class => [GetCustomerTemplates::class, ['customerId' => $customer], Method::GET, $uri, ['CustomerId' => $customer, 'page' => 1, 'limit' => 100], null],
        PostCustomerTemplates::class => [PostCustomerTemplates::class, [$body], Method::POST, $uri, [], $body],
        DeleteCustomerTemplates::class => [DeleteCustomerTemplates::class, [$template, $customer], Method::DELETE, $uri, ['TemplateId' => $template, 'CustomerId' => $customer], null],
        PostCustomerTemplates::class . ' with data' => [PostCustomerTemplates::class, [fn (): CustomerDefaultTemplatesPostData => CustomerDefaultTemplatesPostData::from($body)], Method::POST, $uri, [], $body],
    ],
    'resources' => [
        'ref customer templates get' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->templates()->get(customerId: $customer), GetCustomerTemplates::class, Method::GET, $uri, ['CustomerId' => $customer, 'page' => 1, 'limit' => 100], null],
        'ref customer templates paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->templates()->paginate()->current(), GetCustomerTemplates::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'ref customer templates post' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->templates()->post($body), PostCustomerTemplates::class, Method::POST, $uri, [], $body],
        'ref customer templates post with data' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->templates()->post(CustomerDefaultTemplatesPostData::from($body)), PostCustomerTemplates::class, Method::POST, $uri, [], $body],
        'ref customer templates delete' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->templates()->delete($template, $customer), DeleteCustomerTemplates::class, Method::DELETE, $uri, ['TemplateId' => $template, 'CustomerId' => $customer], null],
    ],
    'dtos' => [
        GetCustomerTemplates::class => [GetCustomerTemplates::class, [], Cin7Payloads::load('ref/customer/templates', 'get.response'), CustomerDefaultTemplateData::class, 'CustomerTemplates'],
        PostCustomerTemplates::class => [PostCustomerTemplates::class, [[]], Cin7Payloads::load('ref/customer/templates', 'post.response'), CustomerDefaultTemplateData::class, 'CustomerTemplates'],
        DeleteCustomerTemplates::class => [DeleteCustomerTemplates::class, ['', ''], Cin7Payloads::load('ref/customer/templates', 'delete.response'), CustomerDefaultTemplateData::class, 'CustomerTemplates'],
    ],
    'bodies' => [
        CustomerDefaultTemplatesPostData::class => [CustomerDefaultTemplatesPostData::class, Cin7Payloads::load('ref/customer/templates', 'post.request')],
    ],
    'missing' => [
        'customer default template without TemplateID' => [CustomerDefaultTemplateData::class, ['CustomerID' => $customer]],
        'customer default template without CustomerID' => [CustomerDefaultTemplateData::class, ['TemplateID' => $template]],
    ],
    'required' => [
        CustomerDefaultTemplateData::class => ['CustomerID', 'TemplateID'],
        CustomerDefaultTemplatesPostData::class => ['CustomerTemplates'],
    ],
];
