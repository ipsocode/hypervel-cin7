<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced Purchase Credit Note Model, one credit note of an advanced purchase's `CreditNote`: the
 * credit note fields of `AbstractPurchaseCreditNoteData`, with the `TaskID` the model requires and
 * `Refunds` (Purchase Payment Line Models). Its lines, additional charges and unstock lines are the
 * Purchase Invoice Line, Additional Charge and Unstock Line Models.
 *
 * The model requires `CreditNoteDate`, but the `advanced-purchase` POST, PUT and DELETE examples
 * embed a credit note that is `NOT AVAILABLE` with a `null` one, so it is nullable, for the
 * responses, and `#[Required]`, which only a write body checks. Every example also sends
 * `CreditNoteInvoiceNumber`, which this model does not list (the Advanced purchase credit note
 * partial model requires it); it is modelled, optional, with that table's length (`#[Max(50)]`).
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseCreditNoteData extends AbstractPurchaseCreditNoteData
{
    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     * @param null|list<PurchasePaymentLineData> $Refunds
     */
    public function __construct(
        string $CreditNoteNumber,
        TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[Uuid]
        public string $TaskID,
        #[Required]
        #[DateTime]
        public ?string $CreditNoteDate = null,
        #[Max(50)]
        public ?string $CreditNoteInvoiceNumber = null,
        #[DataCollectionOf(PurchasePaymentLineData::class)]
        public ?array $Refunds = null,
    ) {
        parent::__construct($CreditNoteNumber, $Status, $Lines, $Unstock);
    }
}
