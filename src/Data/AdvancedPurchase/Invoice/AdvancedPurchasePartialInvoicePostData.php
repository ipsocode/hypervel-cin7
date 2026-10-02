<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Invoice;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The body of `advanced-purchase/invoice` POST: the Advanced purchase invoice partial model with
 * the purchase's `PurchaseID`, which the Available Fields for Purchase Invoice table requires and
 * the POST example sends beside the invoice's fields. It requires the `TaskID` and
 * `CombineAdditionalCharges`, takes a `Status` of `DRAFT` or `AUTHORISED`, and leaves the totals,
 * which POST does not require, optional. The response is `AdvancedPurchaseInvoicesData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialInvoicePostData extends AbstractPurchaseInvoiceData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     */
    public function __construct(
        string $InvoiceDate,
        string $InvoiceDueDate,
        #[In(InvoiceStatus::Draft, InvoiceStatus::Authorised)]
        public InvoiceStatus $Status,
        array $Lines,
        #[Uuid]
        public string $PurchaseID,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public ?float $InvoiceTotalAmount = null,
        public ?float $InvoiceTotalTaxAmount = null,
    ) {
        parent::__construct($InvoiceDate, $InvoiceDueDate, $Status, $Lines);
    }
}
