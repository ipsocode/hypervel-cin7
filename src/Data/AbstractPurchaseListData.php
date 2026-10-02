<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\InvoiceStatus;
use Ipsocode\Cin7\Enums\InvoicingStatus;
use Ipsocode\Cin7\Enums\PurchasePaymentStatus;
use Ipsocode\Cin7\Enums\PurchaseType;
use Ipsocode\Cin7\Enums\ReceivingStatus;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Purchase List and Purchase Credit Note List tables share, field for field: one
 * row of `purchaseList` and of `purchaseCreditNoteList`. The tables differ only in the types they
 * list, and `PurchaseType` has both lists' values, so each final child adds only its response.
 *
 * Both tables require the fields this constructor takes; the optional fields declared here are
 * set through `from()`. `Status` is a string, since the credit note list's example sends a status
 * the Available Purchase Statuses do not list.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseListData extends Data
{
    #[Uuid]
    public ?string $ID = null;

    public ?bool $BlindReceipt = null;

    #[Max(256)]
    public ?string $OrderNumber = null;

    #[Max(50)]
    public ?string $Status = null;

    #[Date]
    public ?string $OrderDate = null;

    #[Date]
    public ?string $InvoiceDate = null;

    #[Max(256)]
    public ?string $Supplier = null;

    #[Uuid]
    public ?string $SupplierID = null;

    #[Max(256)]
    public ?string $InvoiceNumber = null;

    public ?float $InvoiceAmount = null;

    public ?float $PaidAmount = null;

    #[Date]
    public ?string $InvoiceDueDate = null;

    #[Date]
    public ?string $RequiredBy = null;

    #[Max(3)]
    public ?string $BaseCurrency = null;

    #[Max(3)]
    public ?string $SupplierCurrency = null;

    #[Max(256)]
    public ?string $CreditNoteNumber = null;

    public ?TaskStatus $OrderStatus = null;

    public ?TaskStatus $StockReceivedStatus = null;

    public ?TaskStatus $UnstockStatus = null;

    public ?InvoiceStatus $InvoiceStatus = null;

    public ?TaskStatus $CreditNoteStatus = null;

    #[DateTime]
    public ?string $LastUpdatedDate = null;

    public ?bool $IsServiceOnly = null;

    #[Uuid]
    public ?string $DropShipTaskID = null;

    public function __construct(
        public ReceivingStatus $CombinedReceivingStatus,
        public InvoicingStatus $CombinedInvoiceStatus,
        public PurchasePaymentStatus $CombinedPaymentStatus,
        public PurchaseType $Type,
    ) {
    }
}
