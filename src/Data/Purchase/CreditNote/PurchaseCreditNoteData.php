<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\AbstractPurchaseCreditNoteData;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Credit Note Model and the Available Fields for Purchase Credit Note table, the response
 * of `purchase/creditnote`. One name, so one class carrying the union: the table's `TaskID` and
 * `CombineAdditionalCharges` are nullable and `#[Required]`, as the table requires them and the
 * `purchase` examples embed the credit note without them; the model's `Refunds` is optional, as
 * only the model has it. The POST body is `PurchaseCreditNotePostData`.
 *
 * It follows the tables, whose `Unstock` is a list of lines. The `purchase` examples embed the
 * credit note with `Unstock` as an object, `{Status, Lines}`, which this class does not read: a
 * purchase's `CreditNote` is `SimplePurchaseCreditNoteData`.
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
        TaskStatus $Status,
        array $Lines,
        array $Unstock,
        #[DateTime]
        public string $CreditNoteDate,
        #[Required]
        #[Uuid]
        public ?string $TaskID = null,
        #[Required]
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Refunds = null,
    ) {
        parent::__construct($CreditNoteNumber, $Status, $Lines, $Unstock);
    }
}
