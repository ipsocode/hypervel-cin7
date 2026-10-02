<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref\Supplier;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Supplier\Deposits\GetSupplierDeposits;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class DepositsResource extends BaseResource
{
    /**
     * One page of supplier deposits; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $supplierId only this supplier's deposits
     * @param null|bool $showUsedDeposits include the deposits already used
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $supplierId = null,
        ?bool $showUsedDeposits = null,
    ): Response {
        return $this->connector->send(new GetSupplierDeposits(
            $page,
            $limit,
            $supplierId,
            $showUsedDeposits,
        ));
    }

    /**
     * Every page of supplier deposits, fetched as they are walked; call `startPage()` on the
     * paginator to begin later. The envelope has no `Total`, so `pool()` stops at page one; walk
     * with `items()`.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $supplierId only this supplier's deposits
     * @param null|bool $showUsedDeposits include the deposits already used
     */
    public function paginate(
        ?int $limit = null,
        ?string $supplierId = null,
        ?bool $showUsedDeposits = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetSupplierDeposits(
            null,
            $limit,
            $supplierId,
            $showUsedDeposits,
        ));
    }
}
