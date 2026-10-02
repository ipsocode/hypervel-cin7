<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

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
        TaskStatus $Status,
        string $CreditNoteDate,
        public string $CreditNoteInvoiceNumber,
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
