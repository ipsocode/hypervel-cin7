<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityAdditionalChargeData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityLineData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityPostData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityPutData;
use Ipsocode\Cin7\Requests\Crm\Opportunity\GetCrmOpportunity;
use Ipsocode\Cin7\Requests\Crm\Opportunity\PostCrmOpportunity;
use Ipsocode\Cin7\Requests\Crm\Opportunity\PutCrmOpportunity;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `crm/opportunity`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/crm/opportunity';

return [
    'requests' => [
        GetCrmOpportunity::class => [GetCrmOpportunity::class, ['id' => $id, 'modifiedSince' => '2021-06-19T00:00:00'], Method::GET, $uri, ['ID' => $id, 'ModifiedSince' => '2021-06-19T00:00:00', 'page' => 1, 'limit' => 100], null],
        PostCrmOpportunity::class => [PostCrmOpportunity::class, [['Name' => 'A']], Method::POST, $uri, [], ['Name' => 'A']],
        PutCrmOpportunity::class => [PutCrmOpportunity::class, [['ID' => $id, 'Name' => 'B']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'resources' => [
        'crm opportunity get' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->opportunity()->get(), GetCrmOpportunity::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'crm opportunity paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->opportunity()->paginate(id: $id)->current(), GetCrmOpportunity::class, Method::GET, $uri, ['ID' => $id, 'page' => 1, 'limit' => 100], null],
        'crm opportunity post' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->opportunity()->post(['Name' => 'A']), PostCrmOpportunity::class, Method::POST, $uri, [], ['Name' => 'A']],
        'crm opportunity put' => [fn (Cin7Connector $cin7): mixed => $cin7->crm()->opportunity()->put(['ID' => $id, 'Name' => 'B']), PutCrmOpportunity::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'B']],
    ],
    'dtos' => [
        GetCrmOpportunity::class => [GetCrmOpportunity::class, [], Cin7Payloads::load('crm/opportunity', 'get.response'), OpportunityData::class, 'opportunityList'],
        PostCrmOpportunity::class => [PostCrmOpportunity::class, [[]], Cin7Payloads::load('crm/opportunity', 'post.response'), OpportunityData::class, 'opportunityList'],
        PutCrmOpportunity::class => [PutCrmOpportunity::class, [[]], Cin7Payloads::load('crm/opportunity', 'put.response'), OpportunityData::class, 'opportunityList'],
    ],
    'bodies' => [
        OpportunityPostData::class => [OpportunityPostData::class, Cin7Payloads::load('crm/opportunity', 'post.request')],
        OpportunityPutData::class => [OpportunityPutData::class, Cin7Payloads::load('crm/opportunity', 'put.request')],
    ],
    'missing' => [
        'Opportunity without ID' => [OpportunityData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'get.response')['opportunityList'][0], 'ID')],
        'Opportunity POST without CustomerName' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'CustomerName')],
        'Opportunity POST without BillingAddressLine1' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'BillingAddressLine1')],
        'Opportunity POST without Currency' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'Currency')],
        'Opportunity POST without TaxRule' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'TaxRule')],
        'Opportunity POST without Terms' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'Terms')],
        'Opportunity POST without PriceTier' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'PriceTier')],
        'Opportunity POST without OpportunityLocation' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'OpportunityLocation')],
        'Opportunity POST without CustomerCurrency' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'CustomerCurrency')],
        'Opportunity POST without TermMethod' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'TermMethod')],
        'Opportunity POST without SalesRepresentative' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'SalesRepresentative')],
        'Opportunity POST without ShipToOther' => [OpportunityPostData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'post.request'), 'ShipToOther')],
        'Opportunity PUT without CustomerName' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'CustomerName')],
        'Opportunity PUT without BillingAddressLine1' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'BillingAddressLine1')],
        'Opportunity PUT without Currency' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'Currency')],
        'Opportunity PUT without TaxRule' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'TaxRule')],
        'Opportunity PUT without Terms' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'Terms')],
        'Opportunity PUT without PriceTier' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'PriceTier')],
        'Opportunity PUT without OpportunityLocation' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'OpportunityLocation')],
        'Opportunity PUT without CustomerCurrency' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'CustomerCurrency')],
        'Opportunity PUT without TermMethod' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'TermMethod')],
        'Opportunity PUT without SalesRepresentative' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'SalesRepresentative')],
        'Opportunity PUT without ShipToOther' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'ShipToOther')],
        'Opportunity PUT without ID' => [OpportunityPutData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'put.request'), 'ID')],
        'Opportunity without CustomerName' => [OpportunityData::class, Arr::except(Cin7Payloads::load('crm/opportunity', 'get.response')['opportunityList'][0], 'CustomerName')],
    ],
    'required' => [
        OpportunityData::class => ['CustomerName', 'BillingAddressLine1', 'Currency', 'TaxRule', 'Terms', 'PriceTier', 'OpportunityLocation', 'CustomerCurrency', 'TermMethod', 'SalesRepresentative', 'ShipToOther', 'ID'],
        OpportunityPostData::class => ['CustomerName', 'BillingAddressLine1', 'Currency', 'TaxRule', 'Terms', 'PriceTier', 'OpportunityLocation', 'CustomerCurrency', 'TermMethod', 'SalesRepresentative', 'ShipToOther'],
        OpportunityPutData::class => ['CustomerName', 'BillingAddressLine1', 'Currency', 'TaxRule', 'Terms', 'PriceTier', 'OpportunityLocation', 'CustomerCurrency', 'TermMethod', 'SalesRepresentative', 'ShipToOther', 'ID'],
        OpportunityLineData::class => ['Quantity', 'Price', 'Tax', 'Total'],
        OpportunityAdditionalChargeData::class => ['Description', 'Quantity', 'Amount', 'Tax', 'Total'],
    ],
];
