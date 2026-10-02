<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceAdditionalChargeData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the purchase credit note tables share: the Purchase Credit Note Model and the
 * Available Fields for Purchase Credit Note table (a purchase's `CreditNote`, the response of
 * `purchase/creditnote` and the body of its POST), and the Advanced purchase credit note partial
 * model (a credit note of `advanced-purchase/creditnote` and the body of its POST). The simple and
 * the advanced purchase both use it, so it is at the `src/Data/` root. Each is a final child that
 * adds its own fields.
 *
 * Every credit note needs its `CreditNoteNumber`, `CreditNoteDate`, `Status`, `Lines` and
 * `Unstock`, so each child passes them to this constructor; `AdditionalCharges` and the totals,
 * which POST does not require, are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseCreditNoteData extends Data
{
    /**
     * @var null|list<PurchaseInvoiceAdditionalChargeData>
     */
    #[DataCollectionOf(PurchaseInvoiceAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    public ?float $TotalBeforeTax = null;

    public ?float $Tax = null;

    public ?float $Total = null;

    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     */
    public function __construct(
        public string $CreditNoteNumber,
        #[DateTime]
        public string $CreditNoteDate,
        public TaskStatus $Status,
        #[DataCollectionOf(PurchaseInvoiceLineData::class)]
        public array $Lines,
        #[DataCollectionOf(PurchaseUnStockLineData::class)]
        public array $Unstock,
    ) {
    }
}
