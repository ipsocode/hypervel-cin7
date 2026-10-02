<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransferList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTransferList\StockTransferListData;
use Ipsocode\Cin7\Enums\StockTransferStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET stockTransferList`, the list envelope is keyed `StockTransferList`.
 *
 * @extends ListRequest<list<StockTransferListData>>
 */
final class GetStockTransferList extends ListRequest
{
    protected string $listKey = 'StockTransferList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?StockTransferStatus $status = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'stockTransferList';
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
     * @return list<StockTransferListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): StockTransferListData => StockTransferListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
