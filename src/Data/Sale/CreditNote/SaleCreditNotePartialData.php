<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleFulfilmentPickPackLineData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Sale\SaleInvoiceLineData;

/**
 * Sale Credit Note Invoice Partial Model, one entry of `CreditNotes` in the `sale/creditnote`
 * responses. The fields the reference marks required have no default. `CreditNoteInvoiceNumber`
 * is in every example, and `CreditNoteBalance` and `Payments` in the GET example with
 * `IncludePaymentInfo`, though the table omits them.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePartialData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     * @param null|list<SaleFulfilmentPickPackLineData> $Restock
     * @param null|list<SaleCreditNotePaymentData> $Payments
     */
    public function __construct(
        public string $TaskID,
        public bool $CombineAdditionalCharges,
        public string $Status,
        public string $CreditNoteDate,
        public ?string $CreditNoteInvoiceNumber = null,
        public ?string $Memo = null,
        public ?string $CreditNoteNumber = null,
        public ?float $CreditNoteConversionRate = null,
        #[DataCollectionOf(SaleInvoiceLineData::class)]
        public ?array $Lines = null,
        #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
        public ?array $Restock = null,
        public ?float $CreditNoteBalance = null,
        #[DataCollectionOf(SaleCreditNotePaymentData::class)]
        public ?array $Payments = null,
    ) {
    }
}
