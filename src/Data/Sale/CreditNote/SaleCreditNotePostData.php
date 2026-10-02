<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

/**
 * Sale Credit Note POST Model, the body of `sale/creditnote` POST. The fields the reference marks
 * required have no default; an empty-GUID `TaskID` creates a new credit note.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePostData extends AbstractSaleCreditNoteData
{
    public function __construct(
        public string $SaleID,
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $CreditNoteInvoiceNumber,
        public string $Status,
        public string $CreditNoteDate,
    ) {
    }
}
