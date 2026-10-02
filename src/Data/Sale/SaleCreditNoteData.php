<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Credit Note Model.
 *
 * @see docs/data.md
 */
final class SaleCreditNoteData extends Data
{
    /**
     * @param list<SaleInvoiceLineData>|Optional $Lines
     * @param list<SaleInvoiceAdditionalChargeData>|Optional $AdditionalCharges
     * @param list<SalePaymentLineData>|Optional $Refunds
     * @param list<SaleFulfilmentPickPackLineData>|Optional $Restock
     */
    public function __construct(
        public string|Optional $TaskID,
        public string|Optional $CreditNoteInvoiceNumber,
        public string|Optional $Memo,
        public string|Optional $Status,
        public string|Optional $CreditNoteDate,
        public string|Optional $CreditNoteNumber,
        public float|Optional $CreditNoteConversionRate,
        #[DataCollectionOf(SaleInvoiceLineData::class)]
        public array|Optional $Lines,
        #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
        public array|Optional $AdditionalCharges,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public array|Optional $Refunds,
        #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
        public array|Optional $Restock,
        public float|Optional $TotalBeforeTax,
        public float|Optional $Tax,
        public float|Optional $Total,
    ) {
    }
}
