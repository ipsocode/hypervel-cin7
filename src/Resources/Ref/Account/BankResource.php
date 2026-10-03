<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref\Account;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Account\Bank\GetAccountBank;

/**
 * `ref/account/bank`, the bank accounts.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class BankResource extends BaseResource
{
    /**
     * One page of bank accounts; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the bank account with this ID
     * @param null|string $name only bank accounts whose name starts with this
     * @param null|string $bank only bank accounts with this bank
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $bank = null,
    ): Response {
        return $this->connector->send(new GetAccountBank($page, $limit, $id, $name, $bank));
    }

    /**
     * Every page of bank accounts, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the bank account with this ID
     * @param null|string $name only bank accounts whose name starts with this
     * @param null|string $bank only bank accounts with this bank
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $bank = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetAccountBank(null, $limit, $id, $name, $bank));
    }
}
