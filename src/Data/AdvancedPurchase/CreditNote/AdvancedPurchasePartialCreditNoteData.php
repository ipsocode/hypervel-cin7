<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced purchase credit note partial model, one credit note of an advanced purchase (an item
 * of the `CreditNotes` that every `advanced-purchase/creditnote` action answers with): the
 * purchase credit note's fields, with the `TaskID`, `CombineAdditionalCharges`,
 * `CreditNoteInvoiceNumber` and `CreditNoteDate` the table requires. The POST body is
 * `AdvancedPurchasePartialCreditNotePostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialCreditNoteData extends AbstractPurchaseCreditNoteData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     */
    public function __construct(
        string $CreditNoteNumber,
        TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        #[Max(50)]
        public string $CreditNoteInvoiceNumber,
        #[DateTime]
        public string $CreditNoteDate,
    ) {
        parent::__construct($CreditNoteNumber, $Status, $Lines, $Unstock);
    }
}
