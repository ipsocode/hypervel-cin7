<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\SaleList;

use DateTimeInterface;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Enums\OrderStatus;
use Ipsocode\Cin7\Enums\PackingStatus;
use Ipsocode\Cin7\Enums\PickingStatus;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\ShippingStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET saleList` — the list envelope is keyed `SaleList`.
 *
 * @extends ListRequest<SaleListData>
 */
final class GetSaleList extends ListRequest
{
    protected string $listKey = 'SaleList';

    protected string $item = SaleListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $search = null,
        protected readonly DateTimeInterface|string|null $createdSince = null,
        protected readonly DateTimeInterface|string|null $updatedSince = null,
        protected readonly DateTimeInterface|string|null $updatedUntil = null,
        protected readonly DateTimeInterface|string|null $shipBy = null,
        protected readonly ?TaskStatus $quoteStatus = null,
        protected readonly ?OrderStatus $orderStatus = null,
        protected readonly ?PickingStatus $combinedPickStatus = null,
        protected readonly ?PackingStatus $combinedPackStatus = null,
        protected readonly ?ShippingStatus $combinedShippingStatus = null,
        protected readonly ?string $combinedInvoiceStatus = null,
        protected readonly ?TaskStatus $creditNoteStatus = null,
        protected readonly ?string $externalId = null,
        protected readonly ?SaleStatus $status = null,
        protected readonly ?bool $readyForShipping = null,
        protected readonly ?string $orderLocationId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'saleList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Search' => $this->search,
            'CreatedSince' => $this->createdSince,
            'UpdatedSince' => $this->updatedSince,
            'UpdatedUntil' => $this->updatedUntil,
            'ShipBy' => $this->shipBy,
            'QuoteStatus' => $this->quoteStatus,
            'OrderStatus' => $this->orderStatus,
            'CombinedPickStatus' => $this->combinedPickStatus,
            'CombinedPackStatus' => $this->combinedPackStatus,
            'CombinedShippingStatus' => $this->combinedShippingStatus,
            'CombinedInvoiceStatus' => $this->combinedInvoiceStatus,
            'CreditNoteStatus' => $this->creditNoteStatus,
            'ExternalID' => $this->externalId,
            'Status' => $this->status,
            'ReadyForShipping' => $this->readyForShipping,
            'OrderLocationID' => $this->orderLocationId,
        ];
    }
}
