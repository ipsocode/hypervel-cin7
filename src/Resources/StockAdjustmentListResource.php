<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\StockAdjustmentList\GetStockAdjustmentList;

/**
 * `stockadjustmentList`, the stock adjustment list.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockAdjustmentListResource extends BaseResource
{
    /**
     * One page of stock adjustments; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only stock adjustments with this status
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?CompletionStatus $status = null,
    ): Response {
        return $this->connector->send(new GetStockAdjustmentList(
            $page,
            $limit,
            $status,
        ));
    }

    /**
     * Every page of stock adjustments, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only stock adjustments with this status
     */
    public function paginate(
        ?int $limit = null,
        ?CompletionStatus $status = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetStockAdjustmentList(
            null,
            $limit,
            $status,
        ));
    }
}
