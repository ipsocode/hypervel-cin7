<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * Sale Credit Note POST Model, the body of `sale/creditnote` POST. The fields the reference marks
 * required have no default; an empty-GUID `TaskID` creates a new credit note.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePostData extends AbstractSaleCreditNoteData
{
    public function __construct(
        #[Uuid]
        public string $SaleID,
        string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $CreditNoteInvoiceNumber,
        string $Status,
        string $CreditNoteDate,
    ) {
        parent::__construct($TaskID, $Status, $CreditNoteDate);
    }
}
