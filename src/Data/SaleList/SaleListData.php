<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\SaleList;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\FulfilmentStatus;
use Ipsocode\Cin7\Enums\OrderStatus;
use Ipsocode\Cin7\Enums\PackingStatus;
use Ipsocode\Cin7\Enums\PickingStatus;
use Ipsocode\Cin7\Enums\SalePaymentStatus;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\SaleType;
use Ipsocode\Cin7\Enums\ShippingStatus;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale List, one entry of `SaleList`.
 *
 * @see docs/data.md
 */
final class SaleListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $SaleID,
        #[Max(256)]
        public string $OrderNumber,
        public SaleStatus $Status,
        #[Date]
        public string $OrderDate,
        #[Date]
        #[Max(256)]
        public string $Customer,
        public float $InvoiceAmount,
        public float $PaidAmount,
        #[Max(3)]
        public string $BaseCurrency,
        #[Max(3)]
        public string $CustomerCurrency,
        #[DateTime]
        public string $Updated,
        public TaskStatus $QuoteStatus,
        public OrderStatus $OrderStatus,
        public PickingStatus $CombinedPickingStatus,
        public PackingStatus $CombinedPackingStatus,
        public ShippingStatus $CombinedShippingStatus,
        public FulfilmentStatus $FulFilmentStatus,
        #[Max(20)]
        public string $CombinedInvoiceStatus,
        public TaskStatus $CreditNoteStatus,
        public SalePaymentStatus $CombinedPaymentStatus,
        public SaleType $Type,
        #[Max(256)]
        public string $CombinedTrackingNumbers,
        #[Date]
        public ?string $InvoiceDate = null,
        #[Uuid]
        public ?string $CustomerID = null,
        #[Max(256)]
        public ?string $InvoiceNumber = null,
        #[Max(256)]
        public ?string $CustomerReference = null,
        public ?float $SaleInvoicesTotalAmount = null,
        #[Date]
        public ?string $InvoiceDueDate = null,
        #[Date]
        public ?string $ShipBy = null,
        #[Max(256)]
        public ?string $CreditNoteNumber = null,
        public ?float $CombinedPaymentTotal = null,
        #[Max(32)]
        public ?string $SourceChannel = null,
        #[Max(256)]
        public ?string $ExternalID = null,
        #[Uuid]
        public ?string $OrderLocationID = null,
    ) {
    }
}
