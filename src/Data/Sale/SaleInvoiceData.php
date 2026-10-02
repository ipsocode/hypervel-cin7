<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Invoice Model.
 *
 * @see docs/data.md
 */
final class SaleInvoiceData extends Data
{
    /**
     * @param list<SaleInvoiceLineData>|Optional $Lines
     * @param list<SaleInvoiceAdditionalChargeData>|Optional $AdditionalCharges
     * @param list<SalePaymentLineData>|Optional $Payments
     */
    public function __construct(
        public string|Optional $TaskID,
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
