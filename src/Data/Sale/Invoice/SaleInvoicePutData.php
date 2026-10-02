<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * The body of `sale/invoice` PUT: the Sale Invoice POST Model's fields, of which PUT needs only
 * `SaleID` and `TaskID`. An empty collection deletes the existing records, so set one only to
 * delete on purpose.
 *
 * @see docs/data.md
 */
final class SaleInvoicePutData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        public string $SaleID,
        public string $TaskID,
        public ?bool $CombineAdditionalCharges = null,
        public ?string $Memo = null,
        public ?string $Status = null,
        public ?string $InvoiceDate = null,
        public ?string $InvoiceDueDate = null,
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
