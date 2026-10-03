<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\SaleCreditNoteList;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractSaleListData;
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
 * Sale Credit Note List, one entry of `SaleList` in a `saleCreditNoteList` response. Its table is
 * the Sale List's, but the example sends `QuoteStatus` as `""`, outside the quote statuses, and
 * `CombinedTrackingNumbers` as `null`; so the first is a string and the second nullable, and
 * `#[Required]` as the table requires it, which only a write body checks. `RestockStatus` appears
 * only in the example.
 *
 * @see docs/data.md
 */
final class SaleCreditNoteListData extends AbstractSaleListData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $SaleID,
        string $OrderNumber,
        SaleStatus $Status,
        string $OrderDate,
        string $Customer,
        float $InvoiceAmount,
        float $PaidAmount,
        string $BaseCurrency,
        string $CustomerCurrency,
        string $Updated,
        OrderStatus $OrderStatus,
        PickingStatus $CombinedPickingStatus,
        PackingStatus $CombinedPackingStatus,
        ShippingStatus $CombinedShippingStatus,
        FulfilmentStatus $FulFilmentStatus,
        string $CombinedInvoiceStatus,
        TaskStatus $CreditNoteStatus,
        SalePaymentStatus $CombinedPaymentStatus,
        SaleType $Type,
        #[Max(20)]
        public string $QuoteStatus,
        #[Required]
        #[Max(256)]
        public ?string $CombinedTrackingNumbers = null,
        public ?string $RestockStatus = null,
    ) {
        parent::__construct($SaleID, $OrderNumber, $Status, $OrderDate, $Customer, $InvoiceAmount, $PaidAmount, $BaseCurrency, $CustomerCurrency, $Updated, $OrderStatus, $CombinedPickingStatus, $CombinedPackingStatus, $CombinedShippingStatus, $FulFilmentStatus, $CombinedInvoiceStatus, $CreditNoteStatus, $CombinedPaymentStatus, $Type);
    }
}
