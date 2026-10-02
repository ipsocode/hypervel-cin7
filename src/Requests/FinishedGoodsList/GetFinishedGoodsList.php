<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoodsList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoodsList\FinishedGoodsListData;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET finishedGoodsList`, the list envelope is keyed `FinishedGoods`.
 *
 * @extends ListRequest<list<FinishedGoodsListData>>
 */
final class GetFinishedGoodsList extends ListRequest
{
    protected string $listKey = 'FinishedGoods';

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

    /**
     * @return list<FinishedGoodsListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): FinishedGoodsListData => FinishedGoodsListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
