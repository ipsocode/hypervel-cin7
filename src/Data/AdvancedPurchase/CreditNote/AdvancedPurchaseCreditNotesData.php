<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Available Fields for Purchase Credit Note of `advanced-purchase/creditnote`, the
 * `{PurchaseID, CreditNotes}` envelope every action of that path answers with: the purchase's
 * credit notes. The table requires both fields.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseCreditNotesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<AdvancedPurchasePartialCreditNoteData> $CreditNotes
     */
    public function __construct(
        #[Uuid]
        public string $PurchaseID,
        #[DataCollectionOf(AdvancedPurchasePartialCreditNoteData::class)]
        public array $CreditNotes,
    ) {
    }
}
