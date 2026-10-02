<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

/**
 * The body of `sale/invoice` PUT: the Sale Invoice POST Model's fields, of which PUT needs only
 * `SaleID` and `TaskID`. An empty collection deletes the existing records, so set one only to
 * delete on purpose.
 *
 * @see docs/data.md
 */
final class SaleInvoicePutData extends AbstractSaleInvoiceData
{
    public function __construct(
        public string $SaleID,
        public string $TaskID,
        public ?bool $CombineAdditionalCharges = null,
        public ?string $Status = null,
        public ?string $InvoiceDate = null,
        public ?string $InvoiceDueDate = null,
    ) {
    }
}
