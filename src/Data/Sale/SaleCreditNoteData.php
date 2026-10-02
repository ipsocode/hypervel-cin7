<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * Sale Credit Note Model.
 *
 * @see docs/data.md
 */
final class SaleCreditNoteData extends Data
{
    /**
     * @param null|list<SaleInvoiceLineData> $Lines
     * @param null|list<SaleInvoiceAdditionalChargeData> $AdditionalCharges
     * @param null|list<SalePaymentLineData> $Refunds
     * @param null|list<SaleFulfilmentPickPackLineData> $Restock
     */
    public function __construct(
        public ?string $TaskID = null,
        public ?string $CreditNoteInvoiceNumber = null,
        public ?string $Memo = null,
        public ?string $Status = null,
        public ?string $CreditNoteDate = null,
        public ?string $CreditNoteNumber = null,
        public ?float $CreditNoteConversionRate = null,
        #[DataCollectionOf(SaleInvoiceLineData::class)]
        public ?array $Lines = null,
        #[DataCollectionOf(SaleInvoiceAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Refunds = null,
        #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
        public ?array $Restock = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
    }
}
