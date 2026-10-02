<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * Sale Invoice POST Model, the body of `sale/invoice` POST. The fields the reference marks
 * required have no default; an empty-GUID `TaskID` creates a new invoice. The PUT body is
 * `SaleInvoicePutData`.
 *
 * @see docs/data.md
 */
final class SaleInvoicePostData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        public string $SaleID,
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $Status,
        public string $InvoiceDate,
        public string $InvoiceDueDate,
        public ?string $Memo = null,
        public ?float $CurrencyConversionRate = null,
        public ?string $BillingAddressLine1 = null,
        public ?string $BillingAddressLine2 = null,
        public ?string $LinkedFulfillmentNumber = null,
        #[DataCollectionOf(SaleInvoiceLineData::class)]
        public ?array $Lines = null,
        #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
    ) {
    }
}
