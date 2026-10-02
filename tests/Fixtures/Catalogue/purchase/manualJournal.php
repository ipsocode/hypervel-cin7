<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalData;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalLineData;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalPostData;
use Ipsocode\Cin7\Requests\Purchase\ManualJournal\GetPurchaseManualJournal;
use Ipsocode\Cin7\Requests\Purchase\ManualJournal\PostPurchaseManualJournal;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/manualJournal`; tests/Catalogue.php merges every file's rows by kind.

$line = ['Reference' => 'Rounding', 'Amount' => 2, 'Date' => '2017-12-06T00:00:00', 'Debit' => '720', 'Credit' => '404'];
$lineSent = ['Reference' => 'Rounding', 'Amount' => 2.0, 'Date' => '2017-12-06T00:00:00', 'Debit' => '720', 'Credit' => '404'];

return [
    'requests' => [
        GetPurchaseManualJournal::class => [
            GetPurchaseManualJournal::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            Method::GET,
            '/ExternalApi/v2/purchase/manualJournal',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        PostPurchaseManualJournal::class => [
            PostPurchaseManualJournal::class,
            [['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]]],
            Method::POST,
            '/ExternalApi/v2/purchase/manualJournal',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]],
        ],
        PostPurchaseManualJournal::class . ' with data' => [
            PostPurchaseManualJournal::class,
            [fn (): PurchaseManualJournalPostData => PurchaseManualJournalPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/purchase/manualJournal',
            [],
            ['Status' => 'DRAFT', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'purchase manualJournal get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->manualJournal()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchaseManualJournal::class,
            Method::GET,
            '/ExternalApi/v2/purchase/manualJournal',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase manualJournal post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->manualJournal()->post(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED']),
            PostPurchaseManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/purchase/manualJournal',
            [],
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED'],
        ],
        'purchase manualJournal post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->manualJournal()->post(PurchaseManualJournalPostData::from(['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'AUTHORISED'])),
            PostPurchaseManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/purchase/manualJournal',
            [],
            ['Status' => 'AUTHORISED', 'TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
        ],
    ],
    'dtos' => [
        GetPurchaseManualJournal::class => [GetPurchaseManualJournal::class, ['task-1'], Cin7Payloads::load('purchase/manualJournal', 'get.response'), PurchaseManualJournalData::class, ''],
        PostPurchaseManualJournal::class => [PostPurchaseManualJournal::class, [[]], Cin7Payloads::load('purchase/manualJournal', 'post.response'), PurchaseManualJournalData::class, ''],
    ],
    'bodies' => [
        PurchaseManualJournalPostData::class => [PurchaseManualJournalPostData::class, Cin7Payloads::load('purchase/manualJournal', 'post.request')],
    ],
    'missing' => [
        'purchase manual journal POST without TaskID' => [PurchaseManualJournalPostData::class, Arr::except(Cin7Payloads::load('purchase/manualJournal', 'post.request'), 'TaskID')],
        'purchase manual journal without Status' => [PurchaseManualJournalData::class, Arr::except(Cin7Payloads::load('purchase/manualJournal', 'get.response'), 'Status')],
        'purchase manual journal line without Debit' => [PurchaseManualJournalLineData::class, Arr::except(Cin7Payloads::load('purchase/manualJournal', 'get.response')['Lines'][0], 'Debit')],
    ],
    'required' => [
        PurchaseManualJournalData::class => ['Status'],
        PurchaseManualJournalPostData::class => ['Status', 'TaskID'],
        PurchaseManualJournalLineData::class => ['Amount', 'Date', 'Debit', 'Credit'],
    ],
    'omitted' => [
        PostPurchaseManualJournal::class => [
            PostPurchaseManualJournal::class,
            Cin7Payloads::load('purchase/manualJournal', 'post.request'),
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'Status' => 'DRAFT', 'Lines' => [$line]],
        ],
    ],
];
