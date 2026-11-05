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

// The catalog rows for `disassembly/order`; tests/Catalogue.php merges every file's rows by kind.

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
        PostDisassemblyOrder::class . ' with data' => [
            PostDisassemblyOrder::class,
            [fn (): DisassemblyOrderData => DisassemblyOrderData::from(Cin7Payloads::load('disassembly/order', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/disassembly/order',
            [],
            [
                'TaskID' => '122df91c-1ce5-46e2-86f7-52cc14e7964b',
                'Status' => 'COMPLETED',
                'CompletionDate' => '2017-12-21T00:00:00',
                'OrderLines' => [
                    [
                        'Cost' => 5.0,
                        'Unit' => 'Item',
                        'Account' => '715',
                        'ProductID' => '8879b7f5-3aa7-4c7c-ba2c-d0bcc48ba7c3',
                        'ProductCode' => 'GB3-White',
                        'Name' => 'Golf balls white 3-pack',
                        'Quantity' => 1.0,
                    ],
                ],
                'OrderServiceLines' => [
                    [
                        'Account' => '416',
                        'Amount' => 600.0,
                        'ProductID' => '5c6a9204-275f-4a8b-8a90-317d674a4504',
                        'Name' => 'Half day training - Microsoft Office',
                        'Comments' => 'Test',
                    ],
                ],
            ],
        ],
    ],
    'resources' => [
        'disassembly order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->order()->get($taskId),
            GetDisassemblyOrder::class,
        ],
        'disassembly order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassembly()->order()->post(['TaskID' => $taskId, 'Status' => 'COMPLETED']),
            PostDisassemblyOrder::class,
        ],
    ],
    'dtos' => [
        GetDisassemblyOrder::class => [GetDisassemblyOrder::class, [$taskId], Cin7Payloads::load('disassembly/order', 'get.response'), DisassemblyOrderData::class, ''],
        PostDisassemblyOrder::class => [PostDisassemblyOrder::class, [[]], Cin7Payloads::load('disassembly/order', 'post.response'), DisassemblyOrderData::class, ''],
    ],
    'bodies' => [
        DisassemblyOrderData::class => [DisassemblyOrderData::class, PostDisassemblyOrder::class, Cin7Payloads::load('disassembly/order', 'post.request')],
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
