<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\FinishedGoodsList\GetFinishedGoodsList;

/**
 * `finishedGoodsList`, the finished goods list.
 */
final class FinishedGoodsListResource extends ListResource
{
    /**
     * One page of finished goods; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|FinishedGoodsStatus $status only finished goods with this status
     * @param null|string $search only finished goods with this text in the assembly number, location, status, name, product code, batch or notes
     * @param null|string $saleId only finished goods related to this sale
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?FinishedGoodsStatus $status = null,
        ?string $search = null,
        ?string $saleId = null,
    ): Response {
        return $this->sendList(new GetFinishedGoodsList(
            $page,
            $limit,
            $status,
            $search,
            $saleId,
        ));
    }

    /**
     * Every page of finished goods, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|FinishedGoodsStatus $status only finished goods with this status
     * @param null|string $search only finished goods with this text in the assembly number, location, status, name, product code, batch or notes
     * @param null|string $saleId only finished goods related to this sale
     */
    public function paginate(
        ?int $limit = null,
        ?FinishedGoodsStatus $status = null,
        ?string $search = null,
        ?string $saleId = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetFinishedGoodsList(
            null,
            $limit,
            $status,
            $search,
            $saleId,
        ));
    }
}
