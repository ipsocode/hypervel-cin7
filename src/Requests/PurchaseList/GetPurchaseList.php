<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\PurchaseList;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\PurchaseList\PurchaseListData;
use Ipsocode\Cin7\Enums\InvoiceStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET purchaseList`, the purchases; the list envelope is keyed `PurchaseList`.
 *
 * @extends ListRequest<list<PurchaseListData>>
 */
final class GetPurchaseList extends ListRequest
{
    protected string $listKey = 'PurchaseList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $search = null,
        protected readonly DateTimeInterface|string|null $requiredBy = null,
        protected readonly DateTimeInterface|string|null $updatedSince = null,
        protected readonly DateTimeInterface|string|null $updatedUntil = null,
        protected readonly ?TaskStatus $orderStatus = null,
        protected readonly ?TaskStatus $restockReceivedStatus = null,
        protected readonly ?InvoiceStatus $invoiceStatus = null,
        protected readonly ?TaskStatus $creditNoteStatus = null,
        protected readonly ?TaskStatus $unstockStatus = null,
        protected readonly ?string $status = null,
        protected readonly ?string $dropShipTaskId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'purchaseList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Search' => $this->search,
            'RequiredBy' => $this->requiredBy,
            'UpdatedSince' => $this->updatedSince,
            'UpdatedUntil' => $this->updatedUntil,
            'OrderStatus' => $this->orderStatus,
            'RestockReceivedStatus' => $this->restockReceivedStatus,
            'InvoiceStatus' => $this->invoiceStatus,
            'CreditNoteStatus' => $this->creditNoteStatus,
            'UnstockStatus' => $this->unstockStatus,
            'Status' => $this->status,
            'DropShipTaskID' => $this->dropShipTaskId,
        ];
    }

    /**
     * @return list<PurchaseListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): PurchaseListData => PurchaseListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
