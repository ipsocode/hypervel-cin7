<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\ProductionOrderListStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Production\OrderList\GetProductionOrderList;
use Ipsocode\Cin7\Resources\ListResource;

/**
 * `production/orderList`, the orderList resource.
 */
final class OrderListResource extends ListResource
{
    /**
     * One page of ProductionOrderListItems; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|ProductionOrderListStatus $status only orders with this status
     * @param null|string $search only orders with this text in the product code or name, the order number or the tags
     * @param null|string $locationId only orders at this location
     * @param null|DateTimeInterface|string $requiredByDateFrom only orders required by this date or later
     * @param null|DateTimeInterface|string $requiredByDateTo only orders required by this date or earlier
     * @param null|DateTimeInterface|string $completionDateFrom only orders completed on this date or later
     * @param null|DateTimeInterface|string $completionDateTo only orders completed on this date or earlier
     * @param null|string $sourceTaskId only orders produced by this sale task
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?ProductionOrderListStatus $status = null,
        ?string $search = null,
        ?string $locationId = null,
        DateTimeInterface|string|null $requiredByDateFrom = null,
        DateTimeInterface|string|null $requiredByDateTo = null,
        DateTimeInterface|string|null $completionDateFrom = null,
        DateTimeInterface|string|null $completionDateTo = null,
        ?string $sourceTaskId = null,
    ): Response {
        return $this->sendList(new GetProductionOrderList(
            $page,
            $limit,
            $status,
            $search,
            $locationId,
            $requiredByDateFrom,
            $requiredByDateTo,
            $completionDateFrom,
            $completionDateTo,
            $sourceTaskId,
        ));
    }

    /**
     * Every page of ProductionOrderListItems, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|ProductionOrderListStatus $status only orders with this status
     * @param null|string $search only orders with this text in the product code or name, the order number or the tags
     * @param null|string $locationId only orders at this location
     * @param null|DateTimeInterface|string $requiredByDateFrom only orders required by this date or later
     * @param null|DateTimeInterface|string $requiredByDateTo only orders required by this date or earlier
     * @param null|DateTimeInterface|string $completionDateFrom only orders completed on this date or later
     * @param null|DateTimeInterface|string $completionDateTo only orders completed on this date or earlier
     * @param null|string $sourceTaskId only orders produced by this sale task
     */
    public function paginate(
        ?int $limit = null,
        ?ProductionOrderListStatus $status = null,
        ?string $search = null,
        ?string $locationId = null,
        DateTimeInterface|string|null $requiredByDateFrom = null,
        DateTimeInterface|string|null $requiredByDateTo = null,
        DateTimeInterface|string|null $completionDateFrom = null,
        DateTimeInterface|string|null $completionDateTo = null,
        ?string $sourceTaskId = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetProductionOrderList(
            null,
            $limit,
            $status,
            $search,
            $locationId,
            $requiredByDateFrom,
            $requiredByDateTo,
            $completionDateFrom,
            $completionDateTo,
            $sourceTaskId,
        ));
    }
}
