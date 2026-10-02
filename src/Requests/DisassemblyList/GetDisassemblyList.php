<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\DisassemblyList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\DisassemblyList\DisassemblyListData;
use Ipsocode\Cin7\Enums\DisassemblyStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET disassemblyList`, the list envelope is keyed `Disassemblies`.
 *
 * @extends ListRequest<list<DisassemblyListData>>
 */
final class GetDisassemblyList extends ListRequest
{
    protected string $listKey = 'Disassemblies';

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

    /**
     * @return list<DisassemblyListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): DisassemblyListData => DisassemblyListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
