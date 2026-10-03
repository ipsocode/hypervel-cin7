<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTakeList;

use Ipsocode\Cin7\Data\StockTakeList\StockTakeListData;
use Ipsocode\Cin7\Enums\StockTakeStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET stockTakeList`, the list envelope is keyed `StockAdjustmentList`.
 *
 * @extends ListRequest<StockTakeListData>
 */
final class GetStockTakeList extends ListRequest
{
    protected string $listKey = 'StockAdjustmentList';

    protected string $item = StockTakeListData::class;

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
}
