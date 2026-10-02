<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Enums\StockTransferStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\StockTransferList\GetStockTransferList;

/**
 * `stockTransferList`, the stock transfer list.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockTransferListResource extends BaseResource
{
    /**
     * One page of stock transfers; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|StockTransferStatus $status only stock transfers with this status
     * @param null|string $search only stock transfers with this text in the from or to location, status, a custom field or a note
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?StockTransferStatus $status = null,
        ?string $search = null,
    ): Response {
        return $this->connector->send(new GetStockTransferList(
            $page,
            $limit,
            $status,
            $search,
        ));
    }

    /**
     * Every page of stock transfers, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|StockTransferStatus $status only stock transfers with this status
     * @param null|string $search only stock transfers with this text in the from or to location, status, a custom field or a note
     */
    public function paginate(
        ?int $limit = null,
        ?StockTransferStatus $status = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetStockTransferList(
            null,
            $limit,
            $status,
            $search,
        ));
    }
}
