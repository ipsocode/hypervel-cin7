<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

/**
 * Sale Invoice POST Model, the body of `sale/invoice` POST. The fields the reference marks
 * required have no default; an empty-GUID `TaskID` creates a new invoice. The PUT body is
 * `SaleInvoicePutData`.
 *
 * @see docs/data.md
 */
final class SaleInvoicePostData extends AbstractSaleInvoiceData
{
    public function __construct(
        public string $SaleID,
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $Status,
        public string $InvoiceDate,
        public string $InvoiceDueDate,
    ) {
    }
}
