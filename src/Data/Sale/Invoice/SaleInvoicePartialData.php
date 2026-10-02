<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * Sale Invoice Partial Model, one entry of `Invoices` in the `sale/invoice` responses. The fields
 * the reference marks required have no default.
 *
 * @see docs/data.md
 */
final class SaleInvoicePartialData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     */
    public function __construct(
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $Status,
        public string $InvoiceDate,
        public string $InvoiceDueDate,
        public ?string $InvoiceNumber = null,
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
