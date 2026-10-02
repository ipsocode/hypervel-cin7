<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTakeList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTakeList\StockTakeListData;
use Ipsocode\Cin7\Enums\StockTakeStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET stockTakeList`, the list envelope is keyed `StockAdjustmentList`.
 *
 * @extends ListRequest<list<StockTakeListData>>
 */
final class GetStockTakeList extends ListRequest
{
    protected string $listKey = 'StockAdjustmentList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?StockTakeStatus $status = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'stockTakeList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Status' => $this->status,
        ];
    }

    /**
     * @return list<StockTakeListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): StockTakeListData => StockTakeListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
