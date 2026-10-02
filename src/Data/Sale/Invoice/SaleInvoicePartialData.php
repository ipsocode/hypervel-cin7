<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

/**
 * Sale Invoice Partial Model, one entry of `Invoices` in the `sale/invoice` responses. The fields
 * the reference marks required have no default.
 *
 * @see docs/data.md
 */
final class SaleInvoicePartialData extends AbstractSaleInvoiceData
{
    public function __construct(
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $Status,
        public string $InvoiceDate,
        public string $InvoiceDueDate,
        public ?string $InvoiceNumber = null,
    ) {
    }
}
