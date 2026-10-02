<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The Purchase Invoice Model as a simple purchase embeds it, the `Invoice` of `PurchaseData`. The
 * `purchase` examples send its number as `InvocieNumber`, misspelt, where `purchase/invoice` sends
 * `InvoiceNumber`, and a `DRAFT` or `VOIDED` invoice with a `null` `InvoiceDueDate`, which every
 * invoice table requires: a shape of its own, so it is not `PurchaseInvoiceData`, which keeps the
 * tables' rules. It requires the `InvoiceDate`, `Status` and `Lines` every example sends, and takes
 * the model's `InvoiceNumber` beside the examples' `InvocieNumber`.
 *
 * The split is temporary: the invoice is to fold into `PurchaseInvoiceData`, as the order's
 * `null` `Memo` did into `PurchaseOrderData`, once `AbstractPurchaseInvoiceData` no longer
 * requires `InvoiceDueDate` of every child.
 *
 * @see docs/data.md
 */
final class SimplePurchaseInvoiceData extends Data
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param null|list<PurchaseInvoiceAdditionalChargeData> $AdditionalCharges
     * @param null|list<SalePaymentLineData> $Payments
     */
    public function __construct(
        #[DateTime]
        public string $InvoiceDate,
        public InvoiceStatus $Status,
        #[DataCollectionOf(PurchaseInvoiceLineData::class)]
        public array $Lines,
        #[DateTime]
        public ?string $InvoiceDueDate = null,
        public ?string $InvoiceNumber = null,
        public ?string $InvocieNumber = null,
        #[DataCollectionOf(PurchaseInvoiceAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?float $Paid = null,
    ) {
    }
}
