<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * Sale Invoice Partial Model, one entry of `Invoices` in the `sale/invoice` responses. The fields
 * the reference marks required have no default.
 *
 * @see docs/data.md
 */
final class SaleInvoicePartialData extends AbstractSaleInvoiceData
{
    public function __construct(
        string $TaskID,
        public bool $CombineAdditionalCharges,
        public InvoiceStatus $Status,
        #[DateTime]
        public string $InvoiceDate,
        #[DateTime]
        public string $InvoiceDueDate,
        public ?string $InvoiceNumber = null,
    ) {
        parent::__construct($TaskID);
    }
}
