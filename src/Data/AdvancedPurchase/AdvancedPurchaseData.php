<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseData;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoiceData;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalData;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockData;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Other\InventoryMovementLineData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Enums\InvoicingStatus;
use Ipsocode\Cin7\Enums\PurchasePaymentStatus;
use Ipsocode\Cin7\Enums\PurchaseType;
use Ipsocode\Cin7\Enums\ReceivingStatus;

/**
 * Available Fields for Purchase of `advanced-purchase`, the response of its GET, POST, PUT and
 * DELETE: a simple, advanced or service purchase with its order and lists of stock received, put
 * away, invoices, credit notes and manual journals. It has the simple purchase's fields
 * (`AbstractPurchaseData`), with `ID`, `InventoryAccount`, the currencies, `OrderNumber`,
 * `Status`, `RelatedDropShipSaleTask`, `LastUpdatedDate`, `Attachments` and `InventoryMovements`
 * as on `PurchaseData`, and adds the advanced purchase's own: `OrderDate` (a DateTime, as this
 * table types it), the combined statuses, `Type` and `IsServiceOnly`.
 *
 * `Status` is a string, as on `PurchaseData` and the purchase lists, whose examples send a status
 * the Available Purchase Statuses lack. The combined statuses are the purchase lists'
 * `ReceivingStatus`, `InvoicingStatus` and `PurchasePaymentStatus`, and `Type` their
 * `PurchaseType`, which has this table's three values.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseData extends AbstractPurchaseData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AdvancedPurchaseStockData> $StockReceived
     * @param null|list<AdvancedPurchasePutAwayData> $PutAway
     * @param null|list<AdvancedPurchaseInvoiceData> $Invoice
     * @param null|list<AdvancedPurchaseCreditNoteData> $CreditNote
     * @param null|list<AdvancedPurchaseManualJournalData> $ManualJournals
     * @param null|list<AttachmentLineData> $Attachments
     * @param null|list<InventoryMovementLineData> $InventoryMovements
     */
    public function __construct(
        string $Location,
        #[Max(10)]
        public string $Approach,
        #[Uuid]
        public ?string $ID = null,
        #[Max(50)]
        public ?string $InventoryAccount = null,
        #[Max(3)]
        public ?string $BaseCurrency = null,
        #[Max(3)]
        public ?string $SupplierCurrency = null,
        #[Max(256)]
        public ?string $OrderNumber = null,
        #[DateTime]
        public ?string $OrderDate = null,
        public ?ReceivingStatus $CombinedReceivingStatus = null,
        public ?InvoicingStatus $CombinedInvoiceStatus = null,
        public ?PurchasePaymentStatus $CombinedPaymentStatus = null,
        public ?PurchaseType $Type = null,
        public ?bool $IsServiceOnly = null,
        #[Max(20)]
        public ?string $Status = null,
        #[Uuid]
        public ?string $RelatedDropShipSaleTask = null,
        #[DateTime]
        public ?string $LastUpdatedDate = null,
        public ?PurchaseOrderData $Order = null,
        #[DataCollectionOf(AdvancedPurchaseStockData::class)]
        public ?array $StockReceived = null,
        #[DataCollectionOf(AdvancedPurchasePutAwayData::class)]
        public ?array $PutAway = null,
        #[DataCollectionOf(AdvancedPurchaseInvoiceData::class)]
        public ?array $Invoice = null,
        #[DataCollectionOf(AdvancedPurchaseCreditNoteData::class)]
        public ?array $CreditNote = null,
        #[DataCollectionOf(AdvancedPurchaseManualJournalData::class)]
        public ?array $ManualJournals = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(InventoryMovementLineData::class)]
        public ?array $InventoryMovements = null,
    ) {
        parent::__construct($Location);
    }
}
