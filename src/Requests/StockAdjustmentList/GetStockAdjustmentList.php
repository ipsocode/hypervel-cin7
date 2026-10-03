<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustmentList;

use Ipsocode\Cin7\Data\StockAdjustmentList\StockAdjustmentListData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET stockadjustmentList`, the list envelope is keyed `StockAdjustmentList`.
 *
 * @extends ListRequest<StockAdjustmentListData>
 */
final class GetStockAdjustmentList extends ListRequest
{
    protected string $listKey = 'StockAdjustmentList';

    protected string $item = StockAdjustmentListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?CompletionStatus $status = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'stockadjustmentList';
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
