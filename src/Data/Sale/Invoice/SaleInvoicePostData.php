<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * Sale Invoice POST Model, the body of `sale/invoice` POST and PUT. POST needs `SaleID` and an empty-GUID `TaskID`; PUT needs `SaleID` and `TaskID`. On PUT, an empty collection deletes the existing records, so set one only to delete on purpose.
 *
 * @see docs/data.md
 */
final class SaleInvoicePostData extends Data
{
    /**
     * @param list<SaleInvoiceLineData>|Optional $Lines
     * @param list<SaleInvoiceAdditionalChargeData>|Optional $AdditionalCharges
     */
    public function __construct(
        public string|Optional $SaleID,
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
    ) {
    }
}
