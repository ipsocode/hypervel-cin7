<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockLineData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPutData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStocksData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\DeleteAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\GetAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PostAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PutAdvancedPurchaseStock;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/stock`; tests/Catalogue.php merges every file's rows by kind.

// One line, and as a write sends it: `AbstractPurchaseStockLineData`'s fields, `Date` and
// `Quantity` last.
$line = ['Date' => '2018-04-23T00:00:00', 'Quantity' => 6, 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'BatchSN' => '632154845354'];
$lineSent = ['SKU' => 'Bread', 'BatchSN' => '632154845354', 'Date' => '2018-04-23T00:00:00', 'Quantity' => 6.0];

// A body as it is sent: each line without its read-only `Name` and `Received`.
$withoutReadOnly = static function (array $body): array {
    $body['Lines'] = array_map(static fn (array $line): array => Arr::except($line, ['Name', 'Received']), $body['Lines']);

    return $body;
};

return [
    'requests' => [
        GetAdvancedPurchaseStock::class => [
            GetAdvancedPurchaseStock::class,
            ['5a7fb526-527a-4229-b331-90b6f5535aab'],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/stock',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        PostAdvancedPurchaseStock::class => [
            PostAdvancedPurchaseStock::class,
            [['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []]],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []],
        ],
        PutAdvancedPurchaseStock::class => [
            PutAdvancedPurchaseStock::class,
            [['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'DRAFT', 'Lines' => []]],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'DRAFT', 'Lines' => []],
        ],
        DeleteAdvancedPurchaseStock::class => [
            DeleteAdvancedPurchaseStock::class,
            ['3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/stock',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Void' => 'true'],
            null,
        ],
        PostAdvancedPurchaseStock::class . ' with data' => [
            PostAdvancedPurchaseStock::class,
            [fn (): AdvancedPurchaseStockPostData => AdvancedPurchaseStockPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['Status' => 'DRAFT', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Lines' => [$lineSent]],
        ],
        PutAdvancedPurchaseStock::class . ' with data' => [
            PutAdvancedPurchaseStock::class,
            [fn (): AdvancedPurchaseStockPutData => AdvancedPurchaseStockPutData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'AUTHORISED', 'Lines' => [$line]])],
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'advancedPurchase stock get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->get('5a7fb526-527a-4229-b331-90b6f5535aab'),
            GetAdvancedPurchaseStock::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/stock',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        'advancedPurchase stock post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->post(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []]),
            PostAdvancedPurchaseStock::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'Status' => 'AUTHORISED', 'Lines' => []],
        ],
        'advancedPurchase stock post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->post(AdvancedPurchaseStockPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'AUTHORISED', 'Lines' => [$line]])),
            PostAdvancedPurchaseStock::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Lines' => [$lineSent]],
        ],
        'advancedPurchase stock put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->put(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'DRAFT', 'Lines' => []]),
            PutAdvancedPurchaseStock::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'DRAFT', 'Lines' => []],
        ],
        'advancedPurchase stock put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->put(AdvancedPurchaseStockPutData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Status' => 'DRAFT', 'Lines' => []])),
            PutAdvancedPurchaseStock::class,
            Method::PUT,
            '/ExternalApi/v2/advanced-purchase/stock',
            [],
            ['Status' => 'DRAFT', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Lines' => []],
        ],
        'advancedPurchase stock delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->delete('3320ef94-a7e8-4d81-9588-2a3e14cfca6f'),
            DeleteAdvancedPurchaseStock::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/stock',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f'],
            null,
        ],
        'advancedPurchase stock delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->stock()->delete('3320ef94-a7e8-4d81-9588-2a3e14cfca6f', void: true),
            DeleteAdvancedPurchaseStock::class,
            Method::DELETE,
            '/ExternalApi/v2/advanced-purchase/stock',
            ['TaskID' => '3320ef94-a7e8-4d81-9588-2a3e14cfca6f', 'Void' => 'true'],
            null,
        ],
    ],
    'dtos' => [
        GetAdvancedPurchaseStock::class => [GetAdvancedPurchaseStock::class, ['purchase-1'], Cin7Payloads::load('advanced-purchase/stock', 'get.response'), AdvancedPurchaseStocksData::class, ''],
        PostAdvancedPurchaseStock::class => [PostAdvancedPurchaseStock::class, [[]], Cin7Payloads::load('advanced-purchase/stock', 'post.response'), AdvancedPurchaseStocksData::class, ''],
        PutAdvancedPurchaseStock::class => [PutAdvancedPurchaseStock::class, [[]], Cin7Payloads::load('advanced-purchase/stock', 'put.response'), AdvancedPurchaseStocksData::class, ''],
        DeleteAdvancedPurchaseStock::class => [DeleteAdvancedPurchaseStock::class, ['task-1'], Cin7Payloads::load('advanced-purchase/stock', 'delete.response'), AdvancedPurchaseStocksData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchaseStockPostData::class => [AdvancedPurchaseStockPostData::class, Cin7Payloads::load('advanced-purchase/stock', 'post.request')],
        AdvancedPurchaseStockPutData::class => [AdvancedPurchaseStockPutData::class, Cin7Payloads::load('advanced-purchase/stock', 'put.request')],
    ],
    'missing' => [
        'advanced purchase stock envelope without PurchaseID' => [AdvancedPurchaseStocksData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'get.response'), 'PurchaseID')],
        'advanced purchase stock without TaskID' => [AdvancedPurchaseStockData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'get.response')['StockReceiving'][0], 'TaskID')],
        'advanced purchase stock POST without PurchaseID' => [AdvancedPurchaseStockPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'post.request'), 'PurchaseID')],
        'advanced purchase stock POST without Lines' => [AdvancedPurchaseStockPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'post.request'), 'Lines')],
        'advanced purchase stock PUT without TaskID' => [AdvancedPurchaseStockPutData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'put.request'), 'TaskID')],
        'advanced purchase stock line without Date' => [AdvancedPurchaseStockLineData::class, Arr::except(Cin7Payloads::load('advanced-purchase/stock', 'post.request')['Lines'][0], 'Date')],
    ],
    'required' => [
        AdvancedPurchaseStocksData::class => ['PurchaseID'],
        AdvancedPurchaseStockData::class => ['Status', 'Lines', 'TaskID'],
        AdvancedPurchaseStockPostData::class => ['Status', 'Lines', 'PurchaseID'],
        AdvancedPurchaseStockPutData::class => ['Status', 'Lines', 'PurchaseID', 'TaskID'],
        AdvancedPurchaseStockLineData::class => ['Date', 'Quantity'],
    ],
    'omitted' => [
        PostAdvancedPurchaseStock::class => [
            PostAdvancedPurchaseStock::class,
            Cin7Payloads::load('advanced-purchase/stock', 'post.request'),
            $withoutReadOnly(Cin7Payloads::load('advanced-purchase/stock', 'post.request')),
        ],
        PutAdvancedPurchaseStock::class => [
            PutAdvancedPurchaseStock::class,
            Cin7Payloads::load('advanced-purchase/stock', 'put.request'),
            $withoutReadOnly(Cin7Payloads::load('advanced-purchase/stock', 'put.request')),
        ],
    ],
];
