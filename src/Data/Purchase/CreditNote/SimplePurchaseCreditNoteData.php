<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Data\Purchase\PurchaseUnStockData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The Purchase Credit Note Model as a simple purchase embeds it, the `CreditNote` of
 * `PurchaseData`. The tables type `Unstock` as a list of unstock lines, and `purchase/creditnote`
 * sends one, but every `purchase` example sends an object, `{Status, Lines}`
 * (`PurchaseUnStockData`), and a credit note that is `NOT AVAILABLE` or `VOIDED` with a `null`
 * `CreditNoteDate`, which the tables require: a shape of its own, so it is not
 * `PurchaseCreditNoteData`, which keeps the tables' rules. It requires the `CreditNoteNumber`
 * (`""` before there is one), `Status`, `Lines` and `Unstock` every example sends.
 *
 * @see docs/data.md
 */
final class SimplePurchaseCreditNoteData extends Data
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param null|list<PurchaseInvoiceAdditionalChargeData> $AdditionalCharges
     * @param null|list<SalePaymentLineData> $Refunds
     */
    public function __construct(
        public string $CreditNoteNumber,
        public TaskStatus $Status,
        #[DataCollectionOf(PurchaseInvoiceLineData::class)]
        public array $Lines,
        public PurchaseUnStockData $Unstock,
        #[DateTime]
        public ?string $CreditNoteDate = null,
        #[DataCollectionOf(PurchaseInvoiceAdditionalChargeData::class)]
        public ?array $AdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Refunds = null,
        public ?float $TotalBeforeTax = null,
        public ?float $Tax = null,
        public ?float $Total = null,
    ) {
    }
}
