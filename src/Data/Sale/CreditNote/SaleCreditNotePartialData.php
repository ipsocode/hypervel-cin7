<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;

/**
 * Sale Credit Note Invoice Partial Model, one entry of `CreditNotes` in the `sale/creditnote`
 * responses. The fields the reference marks required have no default. `CreditNoteInvoiceNumber`
 * is in every example, and `CreditNoteBalance` and `Payments` in the GET example with
 * `IncludePaymentInfo`, though the table omits them.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePartialData extends AbstractSaleCreditNoteData
{
    /**
     * @param null|list<SaleCreditNotePaymentData> $Payments
     */
    public function __construct(
        string $TaskID,
        public bool $CombineAdditionalCharges,
        string $Status,
        string $CreditNoteDate,
        public ?string $CreditNoteInvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        public ?float $CreditNoteBalance = null,
        #[DataCollectionOf(SaleCreditNotePaymentData::class)]
        public ?array $Payments = null,
    ) {
        parent::__construct($TaskID, $Status, $CreditNoteDate);
    }
}
