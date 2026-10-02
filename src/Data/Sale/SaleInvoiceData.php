<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * Sale Invoice Model.
 *
 * @see docs/data.md
 */
final class SaleInvoiceData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     * @param null|list<SalePaymentLineData> $Payments
     */
    public function __construct(
        public ?string $TaskID = null,
        public ?string $InvoiceNumber = null,
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
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Payments = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?float $Paid = null,
    ) {
    }
}
