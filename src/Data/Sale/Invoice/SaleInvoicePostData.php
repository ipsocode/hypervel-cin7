<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\InvoiceStatus;

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
        #[Uuid]
        public string $SaleID,
        string $TaskID,
        public bool $CombineAdditionalCharges,
        #[In(InvoiceStatus::Draft, InvoiceStatus::Authorised)]
        public InvoiceStatus $Status,
        #[DateTime]
        public string $InvoiceDate,
        #[DateTime]
        public string $InvoiceDueDate,
    ) {
        parent::__construct($TaskID);
    }
}
