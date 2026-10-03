<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\DisassemblyList;

use Ipsocode\Cin7\Data\DisassemblyList\DisassemblyListData;
use Ipsocode\Cin7\Enums\DisassemblyStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET disassemblyList`, the list envelope is keyed `Disassemblies`.
 *
 * @extends ListRequest<DisassemblyListData>
 */
final class GetDisassemblyList extends ListRequest
{
    protected string $listKey = 'Disassemblies';

    protected string $item = DisassemblyListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?DisassemblyStatus $status = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'disassemblyList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Status' => $this->status,
            'Search' => $this->search,
        ];
    }
}
