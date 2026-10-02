<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;
use Ipsocode\Cin7\Data\Sale\SalePaymentLineData;

/**
 * Sale Invoice Partial Model, one entry of `Invoices` in the `sale/invoice` responses.
 *
 * @see docs/data.md
 */
final class SaleInvoicePartialData extends Data
{
    /**
     * @param list<SaleInvoiceLineData>|Optional $Lines
     * @param list<SaleInvoiceAdditionalChargeData>|Optional $AdditionalCharges
     * @param list<SalePaymentLineData>|Optional $Payments
     */
    public function __construct(
        public string|Optional $TaskID,
        public bool|Optional $CombineAdditionalCharges,
        public string|Optional $InvoiceNumber,
        public string|Optional $Memo,
        public string|Optional $Status,
        public string|Optional $InvoiceDate,
        public string|Optional $InvoiceDueDate,
        public float|Optional $CurrencyConversionRate,
        public string|Optional $BillingAddressLine1,
        public string|Optional $BillingAddressLine2,
        public string|Optional $LinkedFulfillmentNumber,
        #[DataCollectionOf(SaleInvoiceLineData::class)]
        public array|Optional $Lines,
        #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
        public array|Optional $AdditionalCharges,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public array|Optional $Payments,
        public float|Optional $TotalBeforeTax,
        public float|Optional $Tax,
        public float|Optional $Total,
        public float|Optional $Paid,
    ) {
    }
}
