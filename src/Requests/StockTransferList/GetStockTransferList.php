<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransferList;

use Ipsocode\Cin7\Data\StockTransferList\StockTransferListData;
use Ipsocode\Cin7\Enums\StockTransferStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET stockTransferList`, the list envelope is keyed `StockTransferList`.
 *
 * @extends ListRequest<StockTransferListData>
 */
final class GetStockTransferList extends ListRequest
{
    protected string $listKey = 'StockTransferList';

    protected string $item = StockTransferListData::class;

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
}
