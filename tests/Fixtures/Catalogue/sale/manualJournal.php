<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalPostData;
use Ipsocode\Cin7\Requests\Sale\ManualJournal\GetSaleManualJournal;
use Ipsocode\Cin7\Requests\Sale\ManualJournal\PostSaleManualJournal;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/manualJournal`; tests/Catalogue.php merges every file's rows by kind.

$line = ['Amount' => 2, 'Date' => '2017-11-28T00:00:00', 'Debit' => '403', 'Credit' => '433'];
$lineSent = ['Amount' => 2.0, 'Date' => '2017-11-28T00:00:00', 'Debit' => '403', 'Credit' => '433'];

return [
    'requests' => [
        GetSaleManualJournal::class => [
            GetSaleManualJournal::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4'],
            Method::GET,
            '/ExternalApi/v2/sale/manualJournal',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        PostSaleManualJournal::class => [
            PostSaleManualJournal::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'DRAFT', 'Lines' => [$line]]],
            Method::POST,
            '/ExternalApi/v2/sale/manualJournal',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'DRAFT', 'Lines' => [$line]],
        ],
        PostSaleManualJournal::class . ' with data' => [
            PostSaleManualJournal::class,
            [fn (): SaleManualJournalPostData => SaleManualJournalPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'DRAFT', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/sale/manualJournal',
            [],
            ['Status' => 'DRAFT', 'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Lines' => [$lineSent]],
        ],
    ],
    'resources' => [
        'sale manualJournal get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->manualJournal()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
            GetSaleManualJournal::class,
            Method::GET,
            '/ExternalApi/v2/sale/manualJournal',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        'sale manualJournal post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->manualJournal()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'AUTHORISED']),
            PostSaleManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/sale/manualJournal',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'AUTHORISED'],
        ],
        'sale manualJournal post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->manualJournal()->post(SaleManualJournalPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Status' => 'AUTHORISED'])),
            PostSaleManualJournal::class,
            Method::POST,
            '/ExternalApi/v2/sale/manualJournal',
            [],
            ['Status' => 'AUTHORISED', 'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
        ],
    ],
    'dtos' => [
        GetSaleManualJournal::class => [GetSaleManualJournal::class, ['sale-1'], Cin7Payloads::load('sale/manualJournal', 'get.response'), SaleManualJournalData::class, ''],
        PostSaleManualJournal::class => [PostSaleManualJournal::class, [[]], Cin7Payloads::load('sale/manualJournal', 'get.response'), SaleManualJournalData::class, ''],
    ],
    'bodies' => [
        SaleManualJournalPostData::class => [SaleManualJournalPostData::class, Cin7Payloads::load('sale/manualJournal', 'post.request')],
    ],
    'missing' => [
        'manual journal POST without SaleID' => [SaleManualJournalPostData::class, Arr::except(Cin7Payloads::load('sale/manualJournal', 'post.request'), 'SaleID')],
    ],
    'required' => [
        SaleManualJournalPostData::class => ['Status', 'SaleID'],
    ],
];
