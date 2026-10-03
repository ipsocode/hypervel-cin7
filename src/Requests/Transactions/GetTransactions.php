<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Transactions;

use DateTimeInterface;
use Ipsocode\Cin7\Data\Transactions\TransactionData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET transactions` — the list envelope is keyed `Transactions`.
 *
 * @extends ListRequest<TransactionData>
 */
final class GetTransactions extends ListRequest
{
    protected string $listKey = 'Transactions';

    protected string $item = TransactionData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly DateTimeInterface|string|null $fromDate = null,
        protected readonly DateTimeInterface|string|null $toDate = null,
        protected readonly ?string $account = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'transactions';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'FromDate' => $this->fromDate,
            'ToDate' => $this->toDate,
            'Account' => $this->account,
        ];
    }
}
