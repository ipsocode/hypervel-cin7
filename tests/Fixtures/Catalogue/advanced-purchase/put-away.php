<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayLineData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwaysData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway\GetAdvancedPurchasePutAway;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway\PostAdvancedPurchasePutAway;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/put-away`; tests/Catalogue.php merges every file's rows by kind.

$line = ['Date' => '2018-04-20T00:00:00', 'Quantity' => 4, 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Location' => 'Main Warehouse', 'BatchSN' => '6318846844'];
$lineSent = ['Date' => '2018-04-20T00:00:00', 'Quantity' => 4.0, 'SKU' => 'Bread', 'Location' => 'Main Warehouse', 'BatchSN' => '6318846844'];

// A body as it is sent: each line without its read-only `Name` and `Received`.
$withoutReadOnly = static function (array $body): array {
    $body['Lines'] = array_map(static fn (array $line): array => Arr::except($line, ['Name', 'Received']), $body['Lines']);

    return $body;
};

return [
    'requests' => [
        GetAdvancedPurchasePutAway::class => [
            GetAdvancedPurchasePutAway::class,
            ['5a7fb526-527a-4229-b331-90b6f5535aab'],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/put-away',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        PostAdvancedPurchasePutAway::class => [
            PostAdvancedPurchasePutAway::class,
            [['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []]],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/put-away',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []],
        ],
        PostAdvancedPurchasePutAway::class . ' with data' => [
            PostAdvancedPurchasePutAway::class,
            [fn (): AdvancedPurchasePutAwayPostData => AdvancedPurchasePutAwayPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/put-away',
            [],
            ['Status' => 'DRAFT', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'advancedPurchase putAway get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->putAway()->get('5a7fb526-527a-4229-b331-90b6f5535aab'),
            GetAdvancedPurchasePutAway::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/put-away',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        'advancedPurchase putAway post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->putAway()->post(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []]),
            PostAdvancedPurchasePutAway::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/put-away',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []],
        ],
        'advancedPurchase putAway post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->putAway()->post(AdvancedPurchasePutAwayPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'AUTHORISED', 'Lines' => [$line]])),
            PostAdvancedPurchasePutAway::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/put-away',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Lines' => [$lineSent]],
        ],
    ],
    'dtos' => [
        GetAdvancedPurchasePutAway::class => [GetAdvancedPurchasePutAway::class, ['purchase-1'], Cin7Payloads::load('advanced-purchase/put-away', 'get.response'), AdvancedPurchasePutAwaysData::class, ''],
        PostAdvancedPurchasePutAway::class => [PostAdvancedPurchasePutAway::class, [[]], Cin7Payloads::load('advanced-purchase/put-away', 'post.response'), AdvancedPurchasePutAwaysData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePutAwayPostData::class => [AdvancedPurchasePutAwayPostData::class, Cin7Payloads::load('advanced-purchase/put-away', 'post.request')],
    ],
    'missing' => [
        'advanced purchase put away envelope without PurchaseID' => [AdvancedPurchasePutAwaysData::class, Arr::except(Cin7Payloads::load('advanced-purchase/put-away', 'get.response'), 'PurchaseID')],
        'advanced purchase put away without TaskID' => [AdvancedPurchasePutAwayData::class, Arr::except(Cin7Payloads::load('advanced-purchase/put-away', 'get.response')['PutAway'][0], 'TaskID')],
        'advanced purchase put away POST without PurchaseID' => [AdvancedPurchasePutAwayPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/put-away', 'post.request'), 'PurchaseID')],
        'advanced purchase put away POST without Lines' => [AdvancedPurchasePutAwayPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/put-away', 'post.request'), 'Lines')],
        'advanced purchase put away line without Date' => [AdvancedPurchasePutAwayLineData::class, Arr::except(Cin7Payloads::load('advanced-purchase/put-away', 'post.request')['Lines'][0], 'Date')],
    ],
    'required' => [
        AdvancedPurchasePutAwaysData::class => ['PurchaseID'],
        AdvancedPurchasePutAwayData::class => ['Status', 'Lines', 'TaskID'],
        AdvancedPurchasePutAwayPostData::class => ['Status', 'Lines', 'PurchaseID'],
        AdvancedPurchasePutAwayLineData::class => ['Date', 'Quantity'],
    ],
    'omitted' => [
        PostAdvancedPurchasePutAway::class => [
            PostAdvancedPurchasePutAway::class,
            Cin7Payloads::load('advanced-purchase/put-away', 'post.request'),
            $withoutReadOnly(Cin7Payloads::load('advanced-purchase/put-away', 'post.request')),
        ],
    ],
];
