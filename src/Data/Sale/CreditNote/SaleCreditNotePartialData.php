<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Ipsocode\Cin7\Data\Sale\SaleFulfilmentPickPackLineData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;
use Ipsocode\Cin7\Data\Sale\SalePaymentLineData;

/**
 * Sale Credit Note Partial Model, one entry of `CreditNotes` in the `sale/creditnote` responses. `CreditNoteInvoiceNumber`, `CreditNoteBalance` and `Payments` are modelled from the reference's examples, which the Partial table omits.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePartialData extends Data
{
    /**
     * @param list<SaleInvoiceLineData>|Optional $Lines
     * @param list<SaleInvoiceAdditionalChargeData>|Optional $AdditionalCharges
     * @param list<SalePaymentLineData>|Optional $Refunds
     * @param list<SaleFulfilmentPickPackLineData>|Optional $Restock
     * @param list<SalePaymentLineData>|Optional $Payments
     */
    public function __construct(
        public string|Optional $TaskID,
        public bool|Optional $CombineAdditionalCharges,
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
        public float|Optional $CreditNoteBalance,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public array|Optional $Payments,
        public float|Optional $TotalBeforeTax,
        public float|Optional $Tax,
        public float|Optional $Total,
    ) {
    }
}
