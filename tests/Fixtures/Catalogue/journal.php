<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Journal\JournalData;
use Ipsocode\Cin7\Data\Journal\JournalLineData;
use Ipsocode\Cin7\Data\Journal\JournalPostData;
use Ipsocode\Cin7\Data\Journal\JournalPutData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\Journal\DeleteJournal;
use Ipsocode\Cin7\Requests\Journal\GetJournal;
use Ipsocode\Cin7\Requests\Journal\PostJournal;
use Ipsocode\Cin7\Requests\Journal\PutJournal;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `journal`; tests/Catalogue.php merges every file's rows by kind.

// The fields every journal requires.
$fields = ['Status' => 'DRAFT', 'Currency' => 'USD', 'CurrencyConversionRate' => 50.0, 'EffectiveDate' => '2018-01-20T00:00:00'];
$line = ['Debit' => '260', 'Credit' => '270', 'Amount' => 2.0, 'BaseAmount' => 100.0, 'Reference' => '3'];
$taskId = '0f3dad04-55cc-437c-87fa-b3a99445ecd4';

return [
    'requests' => [
        GetJournal::class => [
            GetJournal::class,
            ['taskId' => $taskId, 'status' => CompletionStatus::Voided, 'search' => 'JR-00006'],
            Method::GET,
            '/ExternalApi/v2/journal',
            ['TaskID' => $taskId, 'Status' => 'VOIDED', 'Search' => 'JR-00006', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostJournal::class => [
            PostJournal::class,
            [['Status' => 'DRAFT', 'Currency' => 'USD']],
            Method::POST,
            '/ExternalApi/v2/journal',
            [],
            ['Status' => 'DRAFT', 'Currency' => 'USD'],
        ],
        PutJournal::class => [
            PutJournal::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/journal',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteJournal::class => [
            DeleteJournal::class,
            [$taskId, true],
            Method::DELETE,
            '/ExternalApi/v2/journal',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostJournal::class . ' with data' => [
            PostJournal::class,
            [fn (): JournalPostData => JournalPostData::from([...$fields, 'Narration' => 'Test', 'Lines' => [$line]])],
            Method::POST,
            '/ExternalApi/v2/journal',
            [],
            ['Narration' => 'Test', 'Lines' => [$line], ...$fields],
        ],
        PutJournal::class . ' with data' => [
            PutJournal::class,
            [fn (): JournalPutData => JournalPutData::from([...$fields, 'TaskID' => $taskId, 'Notes' => 'Edited'])],
            Method::PUT,
            '/ExternalApi/v2/journal',
            [],
            ['TaskID' => $taskId, 'Notes' => 'Edited', ...$fields],
        ],
    ],
    'resources' => [
        'journal get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->get(),
            GetJournal::class,
            Method::GET,
            '/ExternalApi/v2/journal',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'journal paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->paginate(status: CompletionStatus::Completed)->current(),
            GetJournal::class,
            Method::GET,
            '/ExternalApi/v2/journal',
            ['Status' => 'COMPLETED', 'page' => 1, 'limit' => 100],
            null,
        ],
        'journal post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->post(['Status' => 'DRAFT', 'Currency' => 'USD']),
            PostJournal::class,
            Method::POST,
            '/ExternalApi/v2/journal',
            [],
            ['Status' => 'DRAFT', 'Currency' => 'USD'],
        ],
        'journal post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->post(JournalPostData::from($fields)),
            PostJournal::class,
            Method::POST,
            '/ExternalApi/v2/journal',
            [],
            $fields,
        ],
        'journal put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutJournal::class,
            Method::PUT,
            '/ExternalApi/v2/journal',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'journal put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->put(JournalPutData::from([...$fields, 'TaskID' => $taskId])),
            PutJournal::class,
            Method::PUT,
            '/ExternalApi/v2/journal',
            [],
            ['TaskID' => $taskId, ...$fields],
        ],
        'journal delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->delete($taskId),
            DeleteJournal::class,
            Method::DELETE,
            '/ExternalApi/v2/journal',
            ['ID' => $taskId],
            null,
        ],
        'journal delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->journal()->delete($taskId, void: false),
            DeleteJournal::class,
            Method::DELETE,
            '/ExternalApi/v2/journal',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetJournal::class => [GetJournal::class, [], Cin7Payloads::load('journal', 'get.response'), JournalData::class, 'Journals'],
        PostJournal::class => [PostJournal::class, [[]], Cin7Payloads::load('journal', 'post.response'), JournalData::class, 'Journals.0'],
        PutJournal::class => [PutJournal::class, [[]], Cin7Payloads::load('journal', 'put.response'), JournalData::class, 'Journals.0'],
        DeleteJournal::class => [DeleteJournal::class, [$taskId], Cin7Payloads::load('journal', 'delete.response'), JournalData::class, 'Journals.0'],
    ],
    'bodies' => [
        JournalPostData::class => [JournalPostData::class, Cin7Payloads::load('journal', 'post.request')],
        JournalPutData::class => [JournalPutData::class, Cin7Payloads::load('journal', 'put.request')],
    ],
    'missing' => [
        'journal POST without Currency' => [JournalPostData::class, Arr::except(Cin7Payloads::load('journal', 'post.request'), 'Currency')],
        'journal PUT without TaskID' => [JournalPutData::class, Arr::except(Cin7Payloads::load('journal', 'put.request'), 'TaskID')],
        'journal without EffectiveDate' => [JournalData::class, Arr::except(Cin7Payloads::load('journal', 'get.response')['Journals'][0], 'EffectiveDate')],
        'journal line without BaseAmount' => [JournalLineData::class, Arr::except(Cin7Payloads::load('journal', 'post.request')['Lines'][0], 'BaseAmount')],
    ],
    'required' => [
        JournalData::class => ['Status', 'Currency', 'CurrencyConversionRate', 'EffectiveDate'],
        JournalPostData::class => ['Status', 'Currency', 'CurrencyConversionRate', 'EffectiveDate'],
        JournalPutData::class => ['Status', 'Currency', 'CurrencyConversionRate', 'EffectiveDate', 'TaskID'],
        JournalLineData::class => ['Debit', 'Credit', 'Amount', 'BaseAmount'],
    ],
];
