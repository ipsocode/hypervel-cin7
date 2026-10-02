<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\Discount\DiscountLineData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRuleData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulePostData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulePutData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulesPostData;
use Ipsocode\Cin7\Requests\Reference\Discount\GetDiscount;
use Ipsocode\Cin7\Requests\Reference\Discount\PostDiscount;
use Ipsocode\Cin7\Requests\Reference\Discount\PutDiscount;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `reference/discount`; tests/Catalogue.php merges every file's rows by kind.

$ruleId = 'c74ac47e-c5db-4b82-8fe0-ebdbd07a5920';
$rule = ['Name' => 'The second one', 'IsActive' => false, 'Type' => 'Simple', 'DiscountLines' => [['MinValue' => 0, 'MaxValue' => 10, 'DiscountType' => 'MarkupPercent', 'Amount' => 50]]];
$sentRule = ['IsActive' => false, 'Type' => 'Simple', 'DiscountLines' => [['MinValue' => 0.0, 'MaxValue' => 10.0, 'DiscountType' => 'MarkupPercent', 'Amount' => 50.0]], 'Name' => 'The second one'];
$uri = '/ExternalApi/v2/reference/discount';

return [
    'requests' => [
        GetDiscount::class => [
            GetDiscount::class,
            ['id' => $ruleId, 'search' => 'second'],
            Method::GET,
            $uri,
            ['ID' => $ruleId, 'Search' => 'second', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostDiscount::class => [
            PostDiscount::class,
            [['DiscountRules' => [['Name' => 'The second one']]]],
            Method::POST,
            $uri,
            [],
            ['DiscountRules' => [['Name' => 'The second one']]],
        ],
        PutDiscount::class => [
            PutDiscount::class,
            [['ID' => $ruleId, 'Name' => 'v2']],
            Method::PUT,
            $uri,
            [],
            ['ID' => $ruleId, 'Name' => 'v2'],
        ],
        PostDiscount::class . ' with data' => [
            PostDiscount::class,
            [fn (): ProductDiscountRulesPostData => ProductDiscountRulesPostData::from(['DiscountRules' => [$rule]])],
            Method::POST,
            $uri,
            [],
            ['DiscountRules' => [$sentRule]],
        ],
        PutDiscount::class . ' with data' => [
            PutDiscount::class,
            [fn (): ProductDiscountRulePutData => ProductDiscountRulePutData::from(['ID' => $ruleId, 'Name' => 'v2', 'DiscountLines' => [['DiscountType' => 'FreeShipping', 'OrderExceeds' => 100]]])],
            Method::PUT,
            $uri,
            [],
            ['ID' => $ruleId, 'DiscountLines' => [['DiscountType' => 'FreeShipping', 'OrderExceeds' => 100.0]], 'Name' => 'v2'],
        ],
    ],
    'resources' => [
        'reference discount get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->get(search: 'second'),
            GetDiscount::class,
            Method::GET,
            $uri,
            ['Search' => 'second', 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference discount paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->paginate(id: $ruleId)->current(),
            GetDiscount::class,
            Method::GET,
            $uri,
            ['ID' => $ruleId, 'page' => 1, 'limit' => 100],
            null,
        ],
        'reference discount post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->post(['DiscountRules' => [['Name' => 'The second one']]]),
            PostDiscount::class,
            Method::POST,
            $uri,
            [],
            ['DiscountRules' => [['Name' => 'The second one']]],
        ],
        'reference discount post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->post(ProductDiscountRulesPostData::from(['DiscountRules' => [$rule]])),
            PostDiscount::class,
            Method::POST,
            $uri,
            [],
            ['DiscountRules' => [$sentRule]],
        ],
        'reference discount put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->put(['ID' => $ruleId, 'Name' => 'v2']),
            PutDiscount::class,
            Method::PUT,
            $uri,
            [],
            ['ID' => $ruleId, 'Name' => 'v2'],
        ],
        'reference discount put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->reference()->discount()->put(ProductDiscountRulePutData::from(['ID' => $ruleId, 'Name' => 'v2'])),
            PutDiscount::class,
            Method::PUT,
            $uri,
            [],
            ['ID' => $ruleId, 'Name' => 'v2'],
        ],
    ],
    'dtos' => [
        GetDiscount::class => [GetDiscount::class, [], Cin7Payloads::load('reference/discount', 'get.response'), ProductDiscountRuleData::class, 'DiscountRules'],
        PostDiscount::class => [PostDiscount::class, [[]], Cin7Payloads::load('reference/discount', 'post.response'), ProductDiscountRuleData::class, 'DiscountRules.0'],
        PutDiscount::class => [PutDiscount::class, [[]], Cin7Payloads::load('reference/discount', 'put.response'), ProductDiscountRuleData::class, 'DiscountRules.0'],
    ],
    'bodies' => [
        ProductDiscountRulesPostData::class => [ProductDiscountRulesPostData::class, Cin7Payloads::load('reference/discount', 'post.request')],
        ProductDiscountRulePutData::class => [ProductDiscountRulePutData::class, Cin7Payloads::load('reference/discount', 'put.request')],
    ],
    'missing' => [
        'discount POST without DiscountRules' => [ProductDiscountRulesPostData::class, Arr::except(Cin7Payloads::load('reference/discount', 'post.request'), 'DiscountRules')],
        'discount rule POST without Type' => [ProductDiscountRulePostData::class, Arr::except(Cin7Payloads::load('reference/discount', 'post.request')['DiscountRules'][0], 'Type')],
        'discount PUT without ID' => [ProductDiscountRulePutData::class, Arr::except(Cin7Payloads::load('reference/discount', 'put.request'), 'ID')],
        'discount rule without IsActive' => [ProductDiscountRuleData::class, Arr::except(Cin7Payloads::load('reference/discount', 'get.response')['DiscountRules'][0], 'IsActive')],
    ],
    'required' => [
        ProductDiscountRuleData::class => ['ID', 'Name', 'IsActive', 'Type'],
        ProductDiscountRulePostData::class => ['Name', 'IsActive', 'Type'],
        ProductDiscountRulePutData::class => ['Name', 'ID'],
        ProductDiscountRulesPostData::class => ['DiscountRules'],
        DiscountLineData::class => [],
    ],
];
