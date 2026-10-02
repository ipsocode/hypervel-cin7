<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
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
 * The fields the Sale List and Sale Credit Note List tables share, field for field: one row of
 * `saleList` and of `saleCreditNoteList`. Each is a final child that adds its `QuoteStatus` and
 * `CombinedTrackingNumbers`, which the second list's example sends outside the first's types.
 *
 * Both tables require the fields this constructor takes; the optional fields declared here are
 * set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleListData extends Data
{
    #[Date]
    public ?string $InvoiceDate = null;

    #[Uuid]
    public ?string $CustomerID = null;

    #[Max(256)]
    public ?string $InvoiceNumber = null;

    #[Max(256)]
    public ?string $CustomerReference = null;

    #[Date]
    public ?string $InvoiceDueDate = null;

    #[Date]
    public ?string $ShipBy = null;

    #[Max(256)]
    public ?string $CreditNoteNumber = null;

    #[Max(32)]
    public ?string $SourceChannel = null;

    #[Max(256)]
    public ?string $ExternalID = null;

    #[Uuid]
    public ?string $OrderLocationID = null;

    public function __construct(
        #[Uuid]
        public string $SaleID,
        #[Max(256)]
        public string $OrderNumber,
        public SaleStatus $Status,
        #[Date]
        public string $OrderDate,
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
    ) {
    }
}
