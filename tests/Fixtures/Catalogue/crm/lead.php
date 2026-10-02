<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Lead\LeadData;
use Ipsocode\Cin7\Data\Crm\Lead\LeadPostData;
use Ipsocode\Cin7\Data\Crm\Lead\LeadPutData;
use Ipsocode\Cin7\Requests\Crm\Lead\GetCrmLead;
use Ipsocode\Cin7\Requests\Crm\Lead\PostCrmLead;
use Ipsocode\Cin7\Requests\Crm\Lead\PutCrmLead;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `crm/lead`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/crm/lead';

return [
    'requests' => [
        GetCrmLead::class => [GetCrmLead::class, ['id' => $id, 'name' => 'new', 'modifiedSince' => '2021-06-19T00:00:00'], Method::GET, $uri, ['ID' => $id, 'Name' => 'new', 'ModifiedSince' => '2021-06-19T00:00:00', 'page' => 1, 'limit' => 100], null],
        PostCrmLead::class => [PostCrmLead::class, [['Name' => 'A']], Method::POST, $uri, [], ['Name' => 'A']],
        PutCrmLead::class => [PutCrmLead::class, [['ID' => $id, 'Name' => 'B']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'resources' => [
        'crm lead get' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->lead()->get(), GetCrmLead::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'crm lead paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->lead()->paginate(name: 'A')->current(), GetCrmLead::class, Method::GET, $uri, ['Name' => 'A', 'page' => 1, 'limit' => 100], null],
        'crm lead post' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->lead()->post(['Name' => 'A']), PostCrmLead::class, Method::POST, $uri, [], ['Name' => 'A']],
        'crm lead put' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->lead()->put(['ID' => $id, 'Name' => 'B']), PutCrmLead::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'dtos' => [
        GetCrmLead::class => [GetCrmLead::class, [], Cin7Payloads::load('crm/lead', 'get.response'), LeadData::class, 'LeadList'],
        PostCrmLead::class => [PostCrmLead::class, [[]], Cin7Payloads::load('crm/lead', 'post.response'), LeadData::class, 'LeadList'],
        PutCrmLead::class => [PutCrmLead::class, [[]], Cin7Payloads::load('crm/lead', 'put.response'), LeadData::class, 'LeadList'],
    ],
    'bodies' => [
        LeadPostData::class => [LeadPostData::class, Cin7Payloads::load('crm/lead', 'post.request')],
        LeadPutData::class => [LeadPutData::class, Cin7Payloads::load('crm/lead', 'put.request')],
    ],
    'missing' => [
        'Lead POST without LeadStatus' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'LeadStatus')],
        'Lead POST without Name' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'Name')],
        'Lead POST without Currency' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'Currency')],
        'Lead POST without PaymentTerm' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'PaymentTerm')],
        'Lead POST without PriceTier' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'PriceTier')],
        'Lead POST without SalesRepresentative' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'SalesRepresentative')],
        'Lead POST without TaxRule' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'TaxRule')],
        'Lead POST without CloseChance' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'CloseChance')],
        'Lead POST without CloseDate' => [LeadPostData::class, Arr::except(Cin7Payloads::load('crm/lead', 'post.request'), 'CloseDate')],
        'Lead PUT without LeadStatus' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'LeadStatus')],
        'Lead PUT without Name' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'Name')],
        'Lead PUT without Currency' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'Currency')],
        'Lead PUT without PaymentTerm' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'PaymentTerm')],
        'Lead PUT without PriceTier' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'PriceTier')],
        'Lead PUT without SalesRepresentative' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'SalesRepresentative')],
        'Lead PUT without TaxRule' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'TaxRule')],
        'Lead PUT without CloseChance' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'CloseChance')],
        'Lead PUT without CloseDate' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'CloseDate')],
        'Lead PUT without ID' => [LeadPutData::class, Arr::except(Cin7Payloads::load('crm/lead', 'put.request'), 'ID')],
        'Lead without LeadStatus' => [LeadData::class, Arr::except(Cin7Payloads::load('crm/lead', 'get.response')['LeadList'][0], 'LeadStatus')],
    ],
    'required' => [
        LeadData::class => ['LeadStatus', 'Name', 'Currency', 'PaymentTerm', 'PriceTier', 'SalesRepresentative', 'TaxRule', 'CloseChance', 'CloseDate'],
        LeadPostData::class => ['LeadStatus', 'Name', 'Currency', 'PaymentTerm', 'PriceTier', 'SalesRepresentative', 'TaxRule', 'CloseChance', 'CloseDate'],
        LeadPutData::class => ['LeadStatus', 'Name', 'Currency', 'PaymentTerm', 'PriceTier', 'SalesRepresentative', 'TaxRule', 'CloseChance', 'CloseDate', 'ID'],
    ],
];
