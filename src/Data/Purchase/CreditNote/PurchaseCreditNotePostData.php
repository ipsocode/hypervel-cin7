<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\CreditNote;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `purchase/creditnote` POST: the Available Fields for Purchase Credit Note table
 * with the `TaskID`, `CombineAdditionalCharges` and `CreditNoteDate` it requires, a `Status` of
 * `DRAFT` or `AUTHORISED`, and the totals, which POST does not require. The response is
 * `PurchaseCreditNoteData`.
 *
 * @see docs/data.md
 */
final class PurchaseCreditNotePostData extends AbstractPurchaseCreditNoteData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     */
    public function __construct(
        string $CreditNoteNumber,
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        #[DateTime]
        public string $CreditNoteDate,
    ) {
        parent::__construct($CreditNoteNumber, $Status, $Lines, $Unstock);
    }
}
