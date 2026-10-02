<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\SaleList;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
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
        public string|Optional $SaleID,
        public string|Optional $OrderNumber,
        public string|Optional $Status,
        public string|Optional $OrderDate,
        public string|Optional|null $InvoiceDate,
        public string|Optional $Customer,
        public string|Optional $CustomerID,
        public string|Optional|null $InvoiceNumber,
        public string|Optional $CustomerReference,
        public float|Optional $InvoiceAmount,
        public float|Optional $PaidAmount,
        public float|Optional $SaleInvoicesTotalAmount,
        public string|Optional|null $InvoiceDueDate,
        public string|Optional|null $ShipBy,
        public string|Optional $BaseCurrency,
        public string|Optional $CustomerCurrency,
        public string|Optional|null $CreditNoteNumber,
        public string|Optional $Updated,
        public string|Optional $QuoteStatus,
        public string|Optional $OrderStatus,
        public string|Optional $CombinedPickingStatus,
        public string|Optional $CombinedPackingStatus,
        public string|Optional $CombinedShippingStatus,
        public string|Optional $FulFilmentStatus,
        public string|Optional $CombinedInvoiceStatus,
        public float|Optional $CombinedPaymentTotal,
        public string|Optional $CreditNoteStatus,
        public string|Optional $CombinedPaymentStatus,
        public string|Optional $Type,
        public string|Optional $CombinedTrackingNumbers,
        public string|Optional|null $SourceChannel,
        public string|Optional|null $ExternalID,
        public string|Optional $OrderLocationID,
    ) {
    }
}
