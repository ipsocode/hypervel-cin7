<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Other\InventoryMovementLineData;
use Ipsocode\Cin7\Data\Purchase\CreditNote\SimplePurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalData;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockData;

/**
 * Available Fields for Purchase, the response of `purchase` GET, POST, PUT and DELETE: a simple
 * purchase with its order, stock received, invoice, credit note and manual journal. `Status` is a
 * string, as on the purchase lists, whose examples send a status the Available Purchase Statuses
 * lack. `OrderDate` is in every example and in no Purchase table; it is modelled as the purchase
 * lists' `OrderDate`.
 *
 * The invoice is `purchase/invoice`'s `PurchaseInvoiceData`, which reads the `null`
 * `InvoiceDueDate` and the misspelt `InvocieNumber` the examples embed. The examples embed the
 * credit note in a shape of its own (`Unstock` as `{Status, Lines}`), so it is
 * `SimplePurchaseCreditNoteData`, not the `purchase/creditnote` class.
 *
 * @see docs/data.md
 */
final class PurchaseData extends AbstractPurchaseData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttachmentLineData> $Attachments
     * @param null|list<InventoryMovementLineData> $InventoryMovements
     */
    public function __construct(
        string $Approach,
        string $Location,
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
        #[Date]
        public ?string $OrderDate = null,
        #[Max(20)]
        public ?string $Status = null,
        #[Uuid]
        public ?string $RelatedDropShipSaleTask = null,
        #[DateTime]
        public ?string $LastUpdatedDate = null,
        public ?PurchaseOrderData $Order = null,
        public ?PurchaseStockData $StockReceived = null,
        public ?PurchaseInvoiceData $Invoice = null,
        public ?SimplePurchaseCreditNoteData $CreditNote = null,
        public ?PurchaseManualJournalData $ManualJournals = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(InventoryMovementLineData::class)]
        public ?array $InventoryMovements = null,
    ) {
        parent::__construct($Approach, $Location);
    }
}
