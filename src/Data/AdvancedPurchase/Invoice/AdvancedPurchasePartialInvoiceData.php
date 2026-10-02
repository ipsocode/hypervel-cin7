<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Invoice;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseInvoiceData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * Advanced purchase invoice partial model, one invoice of an advanced purchase (an item of the
 * `Invoices` that every `advanced-purchase/invoice` action answers with): the invoice fields of
 * `AbstractPurchaseInvoiceData`, with the `TaskID`, `CombineAdditionalCharges`, `InvoiceDate` and
 * `InvoiceDueDate` the table requires. Its lines and additional charges are the Purchase Invoice
 * Line and Additional Charge Models. The POST body is `AdvancedPurchasePartialInvoicePostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialInvoiceData extends AbstractPurchaseInvoiceData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     */
    public function __construct(
        InvoiceStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        #[DateTime]
        public string $InvoiceDate,
        #[DateTime]
        public string $InvoiceDueDate,
        public ?float $InvoiceTotalAmount = null,
        public ?float $InvoiceTotalTaxAmount = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
