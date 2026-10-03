<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\InvoiceStatus;

/**
 * The body of `sale/invoice` PUT: the Sale Invoice POST Model's fields, which a PUT may send in
 * part: its example sends the `SaleID`, `TaskID` and `Status`, so those stay required, and leaves
 * out the `InvoiceDate` and `InvoiceDueDate` the table requires, so they are optional here. An
 * empty collection deletes the existing records, so set one only to delete on purpose.
 *
 * @see docs/data.md
 */
final class SaleInvoicePutData extends AbstractSaleInvoiceData
{
    public function __construct(
        #[Uuid]
        public string $SaleID,
        string $TaskID,
        #[In(InvoiceStatus::Draft, InvoiceStatus::Authorised)]
        public InvoiceStatus $Status,
        public ?bool $CombineAdditionalCharges = null,
        #[DateTime]
        public ?string $InvoiceDate = null,
        #[DateTime]
        public ?string $InvoiceDueDate = null,
    ) {
        parent::__construct($TaskID);
    }
}
