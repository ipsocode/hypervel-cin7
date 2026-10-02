<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Credit Note Model, a purchase's `CreditNote`, and the Available Fields for Purchase
 * Credit Note table, the response of `purchase/creditnote`. One name, so one class carrying the
 * union: the table's `TaskID` and `CombineAdditionalCharges` and the model's `Refunds` are
 * optional, as only one of the two has them. The POST body is `PurchaseCreditNotePostData`.
 *
 * It follows the tables, whose `Unstock` is a list of lines. The `purchase` examples embed the
 * credit note with `Unstock` as an object, `{Status, Lines}`, which this class does not read.
 *
 * @see docs/data.md
 */
final class PurchaseCreditNoteData extends AbstractPurchaseCreditNoteData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<PurchaseInvoiceLineData> $Lines
     * @param list<PurchaseUnStockLineData> $Unstock
     * @param null|list<SalePaymentLineData> $Refunds
     */
    public function __construct(
        string $CreditNoteNumber,
        string $CreditNoteDate,
        TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[Uuid]
        public ?string $TaskID = null,
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Refunds = null,
    ) {
        parent::__construct($CreditNoteNumber, $CreditNoteDate, $Status, $Lines, $Unstock);
    }
}
