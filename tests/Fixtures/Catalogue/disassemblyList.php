<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\DisassemblyList\DisassemblyListData;
use Ipsocode\Cin7\Enums\DisassemblyStatus;
use Ipsocode\Cin7\Requests\DisassemblyList\GetDisassemblyList;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `disassemblyList`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetDisassemblyList::class => [
            GetDisassemblyList::class,
            ['status' => DisassemblyStatus::WorkInProgress, 'search' => 'Bread'],
            Method::GET,
            '/ExternalApi/v2/disassemblyList',
            ['Status' => 'WORK IN PROGRESS', 'Search' => 'Bread', 'page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'resources' => [
        'disassemblyList get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassemblyList()->get(status: DisassemblyStatus::Draft, search: 'Bin'),
            GetDisassemblyList::class,
            Method::GET,
            '/ExternalApi/v2/disassemblyList',
            ['Status' => 'DRAFT', 'Search' => 'Bin', 'page' => 1, 'limit' => 100],
            null,
        ],
        'disassemblyList paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->disassemblyList()->paginate()->current(),
            GetDisassemblyList::class,
            Method::GET,
            '/ExternalApi/v2/disassemblyList',
            ['page' => 1, 'limit' => 100],
            null,
        ],
    ],
    'dtos' => [
        GetDisassemblyList::class => [GetDisassemblyList::class, [], Cin7Payloads::load('disassemblyList', 'get.response'), DisassemblyListData::class, 'Disassemblies'],
    ],
];
