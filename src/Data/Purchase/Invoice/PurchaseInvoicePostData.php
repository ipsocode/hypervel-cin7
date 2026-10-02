<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Invoice;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The body of `purchase/invoice` POST: the Available Fields for Purchase Invoice table with the
 * `TaskID`, `CombineAdditionalCharges` and `InvoiceDueDate` it requires, a `Status` of `DRAFT` or
 * `AUTHORISED`, and the totals, which POST does not require. The response is
 * `PurchaseInvoiceData`.
 *
 * @see docs/data.md
 */
final class PurchaseInvoicePostData extends AbstractPurchaseInvoiceData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     */
    public function __construct(
        string $InvoiceDate,
        #[In(InvoiceStatus::Draft, InvoiceStatus::Authorised)]
        public InvoiceStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        #[DateTime]
        public string $InvoiceDueDate,
        public ?float $InvoiceTotalAmount = null,
        public ?float $InvoiceTotalTaxAmount = null,
    ) {
        parent::__construct($InvoiceDate, $Status, $Lines);
    }
}
