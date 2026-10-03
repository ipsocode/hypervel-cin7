<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferData;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferPostData;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferPutData;
use Ipsocode\Cin7\Requests\BankTransfer\DeleteBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\GetBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\PostBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\PutBankTransfer;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `bankTransfer`; tests/Catalogue.php merges every file's rows by kind.

// The fields every bank transfer requires.
$fields = ['Status' => 'DRAFT', 'FromAccount' => '198489', 'ToAccount' => '713', 'FromAmount' => 3.0, 'ToAmount' => 6.0, 'Date' => '2018-01-17T00:00:00'];
$taskId = '675e7c7c-f660-49d4-a1d4-0a644c4e177c';

return [
    'requests' => [
        GetBankTransfer::class => [
            GetBankTransfer::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/bankTransfer',
            ['TaskID' => $taskId],
            null,
        ],
        PostBankTransfer::class => [
            PostBankTransfer::class,
            [['Status' => 'DRAFT', 'Reference' => 'Test']],
            Method::POST,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['Status' => 'DRAFT', 'Reference' => 'Test'],
        ],
        PutBankTransfer::class => [
            PutBankTransfer::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::PUT,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        DeleteBankTransfer::class => [
            DeleteBankTransfer::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/bankTransfer',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
        PostBankTransfer::class . ' with data' => [
            PostBankTransfer::class,
            [fn (): BankTransferPostData => BankTransferPostData::from([...$fields, 'Reference' => 'Test', 'Note' => 'Memo'])],
            Method::POST,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['Reference' => 'Test', 'Note' => 'Memo', ...$fields],
        ],
        PutBankTransfer::class . ' with data' => [
            PutBankTransfer::class,
            [fn (): BankTransferPutData => BankTransferPutData::from([...$fields, 'TaskID' => $taskId, 'Reference' => 'Test'])],
            Method::PUT,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['TaskID' => $taskId, 'Reference' => 'Test', ...$fields],
        ],
    ],
    'resources' => [
        'bankTransfer get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->get($taskId),
            GetBankTransfer::class,
            Method::GET,
            '/ExternalApi/v2/bankTransfer',
            ['TaskID' => $taskId],
            null,
        ],
        'bankTransfer post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->post(['Status' => 'DRAFT', 'Reference' => 'Test']),
            PostBankTransfer::class,
            Method::POST,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['Status' => 'DRAFT', 'Reference' => 'Test'],
        ],
        'bankTransfer post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->post(BankTransferPostData::from($fields)),
            PostBankTransfer::class,
            Method::POST,
            '/ExternalApi/v2/bankTransfer',
            [],
            $fields,
        ],
        'bankTransfer put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->put(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PutBankTransfer::class,
            Method::PUT,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
        'bankTransfer put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->put(BankTransferPutData::from([...$fields, 'TaskID' => $taskId])),
            PutBankTransfer::class,
            Method::PUT,
            '/ExternalApi/v2/bankTransfer',
            [],
            ['TaskID' => $taskId, ...$fields],
        ],
        'bankTransfer delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->delete($taskId),
            DeleteBankTransfer::class,
            Method::DELETE,
            '/ExternalApi/v2/bankTransfer',
            ['ID' => $taskId],
            null,
        ],
        'bankTransfer delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->bankTransfer()->delete($taskId, void: false),
            DeleteBankTransfer::class,
            Method::DELETE,
            '/ExternalApi/v2/bankTransfer',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetBankTransfer::class => [GetBankTransfer::class, [$taskId], Cin7Payloads::load('bankTransfer', 'get.response'), BankTransferData::class, ''],
        PostBankTransfer::class => [PostBankTransfer::class, [[]], Cin7Payloads::load('bankTransfer', 'post.response'), BankTransferData::class, ''],
        PutBankTransfer::class => [PutBankTransfer::class, [[]], Cin7Payloads::load('bankTransfer', 'put.response'), BankTransferData::class, ''],
        DeleteBankTransfer::class => [DeleteBankTransfer::class, [$taskId], Cin7Payloads::load('bankTransfer', 'delete.response'), BankTransferData::class, ''],
    ],
    'bodies' => [
        BankTransferPostData::class => [BankTransferPostData::class, Cin7Payloads::load('bankTransfer', 'post.request')],
        BankTransferPutData::class => [BankTransferPutData::class, Cin7Payloads::load('bankTransfer', 'put.request')],
    ],
    'missing' => [
        'bank transfer POST without ToAccount' => [BankTransferPostData::class, Arr::except(Cin7Payloads::load('bankTransfer', 'post.request'), 'ToAccount')],
        'bank transfer PUT without TaskID' => [BankTransferPutData::class, Arr::except(Cin7Payloads::load('bankTransfer', 'put.request'), 'TaskID')],
        'bank transfer without Date' => [BankTransferData::class, Arr::except(Cin7Payloads::load('bankTransfer', 'get.response'), 'Date')],
    ],
    'required' => [
        BankTransferData::class => ['Status', 'FromAccount', 'ToAccount', 'FromAmount', 'ToAmount', 'Date'],
        BankTransferPostData::class => ['Status', 'FromAccount', 'ToAccount', 'FromAmount', 'ToAmount', 'Date'],
        BankTransferPutData::class => ['Status', 'FromAccount', 'ToAccount', 'FromAmount', 'ToAmount', 'Date', 'TaskID'],
    ],
];
