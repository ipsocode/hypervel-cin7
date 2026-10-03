<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\PurchaseCreditNoteList;

use DateTimeInterface;
use Ipsocode\Cin7\Data\PurchaseCreditNoteList\PurchaseCreditNoteListData;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET purchaseCreditNoteList`, the purchases with a credit note; the list envelope is keyed
 * `PurchaseList`, as in `purchaseList`.
 *
 * @extends ListRequest<PurchaseCreditNoteListData>
 */
final class GetPurchaseCreditNoteList extends ListRequest
{
    protected string $listKey = 'PurchaseList';

    protected string $item = PurchaseCreditNoteListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $search = null,
        protected readonly DateTimeInterface|string|null $updatedSince = null,
        protected readonly DateTimeInterface|string|null $updatedUntil = null,
        protected readonly ?TaskStatus $creditNoteStatus = null,
        protected readonly ?string $status = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'purchaseCreditNoteList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Search' => $this->search,
            'UpdatedSince' => $this->updatedSince,
            'UpdatedUntil' => $this->updatedUntil,
            'CreditNoteStatus' => $this->creditNoteStatus,
            'Status' => $this->status,
        ];
    }
}
