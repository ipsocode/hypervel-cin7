<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\InventoryWriteOffList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\InventoryWriteOffList\InventoryWriteOffListData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET inventoryWriteOffList`, the list envelope is keyed `InventoryWriteOffs`.
 *
 * @extends ListRequest<list<InventoryWriteOffListData>>
 */
final class GetInventoryWriteOffList extends ListRequest
{
    protected string $listKey = 'InventoryWriteOffs';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?CompletionStatus $status = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'inventoryWriteOffList';
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
     * @return list<InventoryWriteOffListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): InventoryWriteOffListData => InventoryWriteOffListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
