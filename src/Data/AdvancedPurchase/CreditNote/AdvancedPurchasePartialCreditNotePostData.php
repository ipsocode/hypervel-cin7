<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `advanced-purchase/creditnote` POST: one Advanced purchase credit note partial
 * model with the purchase's `PurchaseID`, which only the example sends (the table keys the
 * envelope with it), a `Status` of `DRAFT` or `AUTHORISED`, and the totals, which POST does not
 * require. The example sends the empty GUID as `TaskID` to create a credit note. The response is
 * `AdvancedPurchaseCreditNotesData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialCreditNotePostData extends AbstractPurchaseCreditNoteData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     */
    public function __construct(
        string $CreditNoteNumber,
        string $CreditNoteDate,
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[Uuid]
        public string $PurchaseID,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        #[Max(50)]
        public string $CreditNoteInvoiceNumber,
    ) {
        parent::__construct($CreditNoteNumber, $CreditNoteDate, $Status, $Lines, $Unstock);
    }
}
