<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyData;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyPostData;
use Ipsocode\Cin7\Requests\Disassembly\DeleteDisassembly;
use Ipsocode\Cin7\Requests\Disassembly\GetDisassembly;
use Ipsocode\Cin7\Requests\Disassembly\PostDisassembly;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `disassembly`; tests/Catalogue.php merges every file's rows by kind.

$taskId = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetDisassembly::class => [
            GetDisassembly::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/disassembly',
            ['TaskID' => $taskId],
            null,
        ],
        PostDisassembly::class => [
            PostDisassembly::class,
            [['Status' => 'DRAFT', 'WIPAccount' => '715']],
            Method::POST,
            '/ExternalApi/v2/disassembly',
            [],
            ['Status' => 'DRAFT', 'WIPAccount' => '715'],
        ],
        DeleteDisassembly::class => [
            DeleteDisassembly::class,
            [$taskId, 'void' => true],
            Method::DELETE,
            '/ExternalApi/v2/disassembly',
            ['ID' => $taskId, 'Void' => 'true'],
            null,
        ],
    ],
    'resources' => [
        'disassembly get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->get($taskId),
            GetDisassembly::class,
            Method::GET,
            '/ExternalApi/v2/disassembly',
            ['TaskID' => $taskId],
            null,
        ],
        'disassembly post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->post(['Status' => 'DRAFT', 'WIPAccount' => '715']),
            PostDisassembly::class,
            Method::POST,
            '/ExternalApi/v2/disassembly',
            [],
            ['Status' => 'DRAFT', 'WIPAccount' => '715'],
        ],
        'disassembly delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->delete($taskId),
            DeleteDisassembly::class,
            Method::DELETE,
            '/ExternalApi/v2/disassembly',
            ['ID' => $taskId],
            null,
        ],
        'disassembly delete with void' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->delete($taskId, void: false),
            DeleteDisassembly::class,
            Method::DELETE,
            '/ExternalApi/v2/disassembly',
            ['ID' => $taskId, 'Void' => 'false'],
            null,
        ],
    ],
    'dtos' => [
        GetDisassembly::class => [GetDisassembly::class, [$taskId], Cin7Payloads::load('disassembly', 'get.response'), DisassemblyData::class, ''],
        PostDisassembly::class => [PostDisassembly::class, [[]], Cin7Payloads::load('disassembly', 'post.response'), DisassemblyData::class, ''],
        DeleteDisassembly::class => [DeleteDisassembly::class, [$taskId], Cin7Payloads::load('disassembly', 'delete.response'), DisassemblyData::class, ''],
    ],
    'bodies' => [
        DisassemblyPostData::class => [DisassemblyPostData::class, Cin7Payloads::load('disassembly', 'post.request')],
    ],
    'missing' => [
        'disassembly POST without Status' => [DisassemblyPostData::class, Arr::except(Cin7Payloads::load('disassembly', 'post.request'), 'Status')],
        'disassembly POST without WIPAccount' => [DisassemblyPostData::class, Arr::except(Cin7Payloads::load('disassembly', 'post.request'), 'WIPAccount')],
        'disassembly POST without Quantity' => [DisassemblyPostData::class, Arr::except(Cin7Payloads::load('disassembly', 'post.request'), 'Quantity')],
    ],
    'required' => [
        DisassemblyPostData::class => ['Status', 'WIPAccount', 'Quantity'],
    ],
];
