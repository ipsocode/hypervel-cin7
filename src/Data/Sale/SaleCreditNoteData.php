<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Data\Sale\CreditNote\AbstractSaleCreditNoteData;

/**
 * Sale Credit Note Model.
 *
 * @see docs/data.md
 */
final class SaleCreditNoteData extends AbstractSaleCreditNoteData
{
    /**
     * @param null|list<SalePaymentLineData> $Refunds
     */
    public function __construct(
        string $TaskID,
        string $Status,
        string $CreditNoteDate,
        public ?string $CreditNoteInvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Refunds = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
        parent::__construct($TaskID, $Status, $CreditNoteDate);
    }
}
