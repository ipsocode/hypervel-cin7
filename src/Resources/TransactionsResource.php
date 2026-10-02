<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Transactions\GetTransactions;

/**
 * `transactions`, the ledger transactions.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class TransactionsResource extends BaseResource
{
    /**
     * One page of transactions; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|DateTimeInterface|string $fromDate only transactions with an effective date after this
     * @param null|DateTimeInterface|string $toDate only transactions with an effective date before this
     * @param null|string $account only transactions with this debit or credit account code
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        DateTimeInterface|string|null $fromDate = null,
        DateTimeInterface|string|null $toDate = null,
        ?string $account = null,
    ): Response {
        return $this->connector->send(new GetTransactions($page, $limit, $fromDate, $toDate, $account));
    }

    /**
     * Every page of transactions, fetched as they are walked; call `startPage()` on the paginator
     * to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|DateTimeInterface|string $fromDate only transactions with an effective date after this
     * @param null|DateTimeInterface|string $toDate only transactions with an effective date before this
     * @param null|string $account only transactions with this debit or credit account code
     */
    public function paginate(
        ?int $limit = null,
        DateTimeInterface|string|null $fromDate = null,
        DateTimeInterface|string|null $toDate = null,
        ?string $account = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetTransactions(null, $limit, $fromDate, $toDate, $account));
    }
}
