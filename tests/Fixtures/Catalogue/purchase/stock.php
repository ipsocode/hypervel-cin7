<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockData;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockLineData;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockPostData;
use Ipsocode\Cin7\Requests\Purchase\Stock\GetPurchaseStock;
use Ipsocode\Cin7\Requests\Purchase\Stock\PostPurchaseStock;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/stock`; tests/Catalogue.php merges every file's rows by kind.

// One line as a response has it, with the read-only `Name` and `Received`, and as a POST sends it:
// the line's own `Location` first, then `AbstractPurchaseStockLineData`'s fields, `Date` and
// `Quantity` last.
$line = ['Date' => '2017-12-08T00:00:00', 'Quantity' => 3, 'SKU' => 'Bread', 'Name' => 'Baked Bread', 'Location' => 'Main Warehouse', 'Received' => false, 'BatchSN' => 'PO-00001-1'];
$lineSent = ['Location' => 'Main Warehouse', 'SKU' => 'Bread', 'BatchSN' => 'PO-00001-1', 'Date' => '2017-12-08T00:00:00', 'Quantity' => 3.0];

// The POST example, and the body sent from it without each line's read-only fields.
$request = Cin7Payloads::load('purchase/stock', 'post.request');
$sent = [...$request, 'Lines' => array_map(static fn (array $item): array => Arr::except($item, ['Name', 'Received']), $request['Lines'])];

return [
    'requests' => [
        GetPurchaseStock::class => [
            GetPurchaseStock::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            Method::GET,
            '/ExternalApi/v2/purchase/stock',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        PostPurchaseStock::class => [
            PostPurchaseStock::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED', 'Lines' => []]],
            Method::POST,
            '/ExternalApi/v2/purchase/stock',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED', 'Lines' => []],
        ],
        PostPurchaseStock::class . ' with data' => [
            PostPurchaseStock::class,
            [fn (): PurchaseStockPostData => PurchaseStockPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/purchase/stock',
            [],
            ['Status' => 'DRAFT', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'purchase stock get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->stock()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchaseStock::class,
            Method::GET,
            '/ExternalApi/v2/purchase/stock',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase stock post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->stock()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]]),
            PostPurchaseStock::class,
            Method::POST,
            '/ExternalApi/v2/purchase/stock',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [Arr::except($line, ['Name', 'Received'])]],
        ],
        'purchase stock post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->stock()->post(PurchaseStockPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED', 'Lines' => []])),
            PostPurchaseStock::class,
            Method::POST,
            '/ExternalApi/v2/purchase/stock',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Lines' => []],
        ],
    ],
    'dtos' => [
        GetPurchaseStock::class => [GetPurchaseStock::class, ['task-1'], Cin7Payloads::load('purchase/stock', 'get.response'), PurchaseStockData::class, ''],
        PostPurchaseStock::class => [PostPurchaseStock::class, [[]], Cin7Payloads::load('purchase/stock', 'post.response'), PurchaseStockData::class, ''],
    ],
    'bodies' => [
        PurchaseStockPostData::class => [PurchaseStockPostData::class, $request],
    ],
    'missing' => [
        'purchase stock POST without TaskID' => [PurchaseStockPostData::class, Arr::except($request, 'TaskID')],
        'purchase stock without Lines' => [PurchaseStockData::class, Arr::except(Cin7Payloads::load('purchase/stock', 'get.response'), 'Lines')],
        'purchase stock line without Date' => [PurchaseStockLineData::class, Arr::except($line, 'Date')],
    ],
    'required' => [
        PurchaseStockData::class => ['Status', 'Lines'],
        PurchaseStockPostData::class => ['Status', 'Lines', 'TaskID'],
        PurchaseStockLineData::class => ['Date', 'Quantity'],
    ],
    'omitted' => [
        PostPurchaseStock::class => [PostPurchaseStock::class, $request, $sent],
    ],
];
