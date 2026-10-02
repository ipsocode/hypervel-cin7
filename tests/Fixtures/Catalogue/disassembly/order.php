<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderData;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderLineData;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderServiceLineData;
use Ipsocode\Cin7\Requests\Disassembly\Order\GetDisassemblyOrder;
use Ipsocode\Cin7\Requests\Disassembly\Order\PostDisassemblyOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `disassembly/order`; tests/Catalogue.php merges every file's rows by kind.

$taskId = '122df91c-1ce5-46e2-86f7-52cc14e7964b';

return [
    'requests' => [
        GetDisassemblyOrder::class => [
            GetDisassemblyOrder::class,
            [$taskId],
            Method::GET,
            '/ExternalApi/v2/disassembly/order',
            ['TaskID' => $taskId],
            null,
        ],
        PostDisassemblyOrder::class => [
            PostDisassemblyOrder::class,
            [['TaskID' => $taskId, 'Status' => 'COMPLETED']],
            Method::POST,
            '/ExternalApi/v2/disassembly/order',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
    ],
    'resources' => [
        'disassembly order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->order()->get($taskId),
            GetDisassemblyOrder::class,
            Method::GET,
            '/ExternalApi/v2/disassembly/order',
            ['TaskID' => $taskId],
            null,
        ],
        'disassembly order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->order()->post(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PostDisassemblyOrder::class,
            Method::POST,
            '/ExternalApi/v2/disassembly/order',
            [],
            ['TaskID' => $taskId, 'Status' => 'COMPLETED'],
        ],
    ],
    'dtos' => [
        GetDisassemblyOrder::class => [GetDisassemblyOrder::class, [$taskId], Cin7Payloads::load('disassembly/order', 'get.response'), DisassemblyOrderData::class, ''],
        PostDisassemblyOrder::class => [PostDisassemblyOrder::class, [[]], Cin7Payloads::load('disassembly/order', 'post.response'), DisassemblyOrderData::class, ''],
    ],
    'bodies' => [
        DisassemblyOrderData::class => [DisassemblyOrderData::class, Cin7Payloads::load('disassembly/order', 'post.request')],
    ],
    'missing' => [
        'disassembly order line without Quantity' => [DisassemblyOrderLineData::class, Arr::except(Cin7Payloads::load('disassembly/order', 'post.request')['OrderLines'][0], 'Quantity')],
        'disassembly order line without Cost' => [DisassemblyOrderLineData::class, Arr::except(Cin7Payloads::load('disassembly/order', 'post.request')['OrderLines'][0], 'Cost')],
        'disassembly order service line without Account' => [DisassemblyOrderServiceLineData::class, Arr::except(Cin7Payloads::load('disassembly/order', 'post.request')['OrderServiceLines'][0], 'Account')],
        'disassembly order service line without Amount' => [DisassemblyOrderServiceLineData::class, Arr::except(Cin7Payloads::load('disassembly/order', 'post.request')['OrderServiceLines'][0], 'Amount')],
    ],
    'required' => [
        DisassemblyOrderLineData::class => ['Quantity', 'Cost'],
        DisassemblyOrderServiceLineData::class => ['Account', 'Amount'],
    ],
];
