<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\SaleList;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale List, one entry of `SaleList`.
 *
 * @see docs/data.md
 */
final class SaleListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public ?string $SaleID = null,
        public ?string $OrderNumber = null,
        public ?string $Status = null,
        public ?string $OrderDate = null,
        public ?string $InvoiceDate = null,
        public ?string $Customer = null,
        public ?string $CustomerID = null,
        public ?string $InvoiceNumber = null,
        public ?string $CustomerReference = null,
        public ?float $InvoiceAmount = null,
        public ?float $PaidAmount = null,
        public ?float $SaleInvoicesTotalAmount = null,
        public ?string $InvoiceDueDate = null,
        public ?string $ShipBy = null,
        public ?string $BaseCurrency = null,
        public ?string $CustomerCurrency = null,
        public ?string $CreditNoteNumber = null,
        public ?string $Updated = null,
        public ?string $QuoteStatus = null,
        public ?string $OrderStatus = null,
        public ?string $CombinedPickingStatus = null,
        public ?string $CombinedPackingStatus = null,
        public ?string $CombinedShippingStatus = null,
        public ?string $FulFilmentStatus = null,
        public ?string $CombinedInvoiceStatus = null,
        public ?float $CombinedPaymentTotal = null,
        public ?string $CreditNoteStatus = null,
        public ?string $CombinedPaymentStatus = null,
        public ?string $Type = null,
        public ?string $CombinedTrackingNumbers = null,
        public ?string $SourceChannel = null,
        public ?string $ExternalID = null,
        public ?string $OrderLocationID = null,
    ) {
    }
}
