<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalsData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchasePartialManualJournalData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchasePartialManualJournalPostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal\GetAdvancedPurchaseManualJournal;
use Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal\PostAdvancedPurchaseManualJournal;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `advanced-purchase/manualJournal`; tests/Catalogue.php merges every file's rows by kind.

$line = ['Reference' => 'Freight', 'Amount' => 20, 'Date' => '2018-04-23T00:00:00', 'Debit' => '715', 'Credit' => '860'];
$lineSent = ['Reference' => 'Freight', 'Amount' => 20.0, 'Date' => '2018-04-23T00:00:00', 'Debit' => '715', 'Credit' => '860'];

return [
    'requests' => [
        GetAdvancedPurchaseManualJournal::class => [
            GetAdvancedPurchaseManualJournal::class,
            ['5a7fb526-527a-4229-b331-90b6f5535aab'],
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        PostAdvancedPurchaseManualJournal::class => [
            PostAdvancedPurchaseManualJournal::class,
            [['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'DRAFT', 'Lines' => [$line]]],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'DRAFT', 'Lines' => [$line]],
        ],
        PostAdvancedPurchaseManualJournal::class . ' with data' => [
            PostAdvancedPurchaseManualJournal::class,
            [fn (): AdvancedPurchasePartialManualJournalPostData => AdvancedPurchasePartialManualJournalPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'DRAFT', 'Lines' => [$line + ['IsSystem' => false]]])],
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            [],
            ['Status' => 'DRAFT', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'advancedPurchase manualJournal get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->manualJournal()->get('5a7fb526-527a-4229-b331-90b6f5535aab'),
            GetAdvancedPurchaseManualJournal::class,
            Method::GET,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab'],
            null,
        ],
        'advancedPurchase manualJournal post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->manualJournal()->post(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'AUTHORISED']),
            PostAdvancedPurchaseManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            [],
            ['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'AUTHORISED'],
        ],
        'advancedPurchase manualJournal post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->advancedPurchase()->manualJournal()->post(AdvancedPurchasePartialManualJournalPostData::from(['PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97', 'Status' => 'AUTHORISED'])),
            PostAdvancedPurchaseManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/advanced-purchase/manualJournal',
            [],
            ['Status' => 'AUTHORISED', 'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab', 'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97'],
        ],
    ],
    'dtos' => [
        GetAdvancedPurchaseManualJournal::class => [GetAdvancedPurchaseManualJournal::class, ['purchase-1'], Cin7Payloads::load('advanced-purchase/manualJournal', 'get.response'), AdvancedPurchaseManualJournalsData::class, ''],
        PostAdvancedPurchaseManualJournal::class => [PostAdvancedPurchaseManualJournal::class, [[]], Cin7Payloads::load('advanced-purchase/manualJournal', 'post.response'), AdvancedPurchaseManualJournalsData::class, ''],
    ],
    'bodies' => [
        AdvancedPurchasePartialManualJournalPostData::class => [AdvancedPurchasePartialManualJournalPostData::class, Cin7Payloads::load('advanced-purchase/manualJournal', 'post.request')],
    ],
    'missing' => [
        'advanced purchase manual journal envelope without PurchaseID' => [AdvancedPurchaseManualJournalsData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'get.response'), 'PurchaseID')],
        'advanced purchase manual journal envelope without ManualJournals' => [AdvancedPurchaseManualJournalsData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'get.response'), 'ManualJournals')],
        'advanced purchase manual journal without TaskID' => [AdvancedPurchasePartialManualJournalData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'get.response')['ManualJournals'][0], 'TaskID')],
        'advanced purchase manual journal without Status' => [AdvancedPurchasePartialManualJournalData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'get.response')['ManualJournals'][0], 'Status')],
        'advanced purchase manual journal POST without PurchaseID' => [AdvancedPurchasePartialManualJournalPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'post.request'), 'PurchaseID')],
        'advanced purchase manual journal POST without TaskID' => [AdvancedPurchasePartialManualJournalPostData::class, Arr::except(Cin7Payloads::load('advanced-purchase/manualJournal', 'post.request'), 'TaskID')],
    ],
    'required' => [
        AdvancedPurchaseManualJournalsData::class => ['PurchaseID', 'ManualJournals'],
        AdvancedPurchasePartialManualJournalData::class => ['Status', 'TaskID'],
        AdvancedPurchasePartialManualJournalPostData::class => ['Status', 'PurchaseID', 'TaskID'],
    ],
    'omitted' => [
        PostAdvancedPurchaseManualJournal::class => [
            PostAdvancedPurchaseManualJournal::class,
            Cin7Payloads::load('advanced-purchase/manualJournal', 'post.request'),
            [
                'PurchaseID' => '5a7fb526-527a-4229-b331-90b6f5535aab',
                'TaskID' => '807c3266-157b-4c0d-8131-a3b8e06c0d97',
                'Status' => 'DRAFT',
                'Lines' => [['Reference' => '', 'Amount' => 20, 'Date' => '2018-04-23T00:00:00', 'Debit' => '715', 'Credit' => '860']],
            ],
        ],
    ],
];
