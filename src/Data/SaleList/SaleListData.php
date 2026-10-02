<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\SaleList;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Attributes\DateTime;

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
        public ?string $SaleID = null,
        #[Max(256)]
        public ?string $OrderNumber = null,
        public ?string $Status = null,
        #[Date]
        public ?string $OrderDate = null,
        #[Date]
        public ?string $InvoiceDate = null,
        #[Date]
        #[Max(256)]
        public ?string $Customer = null,
        #[Uuid]
        public ?string $CustomerID = null,
        #[Max(256)]
        public ?string $InvoiceNumber = null,
        #[Max(256)]
        public ?string $CustomerReference = null,
        public ?float $InvoiceAmount = null,
        public ?float $PaidAmount = null,
        public ?float $SaleInvoicesTotalAmount = null,
        #[Date]
        public ?string $InvoiceDueDate = null,
        #[Date]
        public ?string $ShipBy = null,
        #[Max(3)]
        public ?string $BaseCurrency = null,
        #[Max(3)]
        public ?string $CustomerCurrency = null,
        #[Max(256)]
        public ?string $CreditNoteNumber = null,
        #[DateTime]
        public ?string $Updated = null,
        #[Max(20)]
        public ?string $QuoteStatus = null,
        #[Max(20)]
        public ?string $OrderStatus = null,
        #[Max(20)]
        public ?string $CombinedPickingStatus = null,
        #[Max(20)]
        public ?string $CombinedPackingStatus = null,
        #[Max(20)]
        public ?string $CombinedShippingStatus = null,
        #[Max(20)]
        public ?string $FulFilmentStatus = null,
        #[Max(20)]
        public ?string $CombinedInvoiceStatus = null,
        public ?float $CombinedPaymentTotal = null,
        #[Max(20)]
        public ?string $CreditNoteStatus = null,
        #[Max(20)]
        public ?string $CombinedPaymentStatus = null,
        #[Max(50)]
        public ?string $Type = null,
        #[Max(256)]
        public ?string $CombinedTrackingNumbers = null,
        #[Max(32)]
        public ?string $SourceChannel = null,
        #[Max(256)]
        public ?string $ExternalID = null,
        #[Uuid]
        public ?string $OrderLocationID = null,
    ) {
    }
}
