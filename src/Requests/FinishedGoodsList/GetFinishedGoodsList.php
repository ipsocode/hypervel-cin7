<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoodsList;

use Ipsocode\Cin7\Data\FinishedGoodsList\FinishedGoodsListData;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET finishedGoodsList`, the list envelope is keyed `FinishedGoods`.
 *
 * @extends ListRequest<FinishedGoodsListData>
 */
final class GetFinishedGoodsList extends ListRequest
{
    protected string $listKey = 'FinishedGoods';

    protected string $item = FinishedGoodsListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?FinishedGoodsStatus $status = null,
        protected readonly ?string $search = null,
        protected readonly ?string $saleId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'finishedGoodsList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Status' => $this->status,
            'Search' => $this->search,
            'SaleID' => $this->saleId,
        ];
    }
}
