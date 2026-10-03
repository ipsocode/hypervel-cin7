<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\InventoryWriteOffList\GetInventoryWriteOffList;

/**
 * `inventoryWriteOffList`, the inventory write-off list.
 */
final class InventoryWriteOffListResource extends ListResource
{
    /**
     * One page of inventory write-offs; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only write-offs with this status
     * @param null|string $search only write-offs with this text in the number, location, status or notes
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
    ): Response {
        return $this->sendList(new GetInventoryWriteOffList(
            $page,
            $limit,
            $status,
            $search,
        ));
    }

    /**
     * Every page of inventory write-offs, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only write-offs with this status
     * @param null|string $search only write-offs with this text in the number, location, status or notes
     */
    public function paginate(
        ?int $limit = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetInventoryWriteOffList(
            null,
            $limit,
            $status,
            $search,
        ));
    }
}
