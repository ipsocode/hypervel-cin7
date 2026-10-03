<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\StockTakeStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\StockTakeList\GetStockTakeList;

/**
 * `stockTakeList`, the stock take list.
 */
final class StockTakeListResource extends ListResource
{
    /**
     * One page of stock takes; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|StockTakeStatus $status only stock takes with this status
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?StockTakeStatus $status = null,
    ): Response {
        return $this->sendList(new GetStockTakeList(
            $page,
            $limit,
            $status,
        ));
    }

    /**
     * Every page of stock takes, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|StockTakeStatus $status only stock takes with this status
     */
    public function paginate(
        ?int $limit = null,
        ?StockTakeStatus $status = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetStockTakeList(
            null,
            $limit,
            $status,
        ));
    }
}
