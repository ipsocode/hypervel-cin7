<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Credit Notes, the `{SaleID, CreditNotes}` envelope every `sale/creditnote` action answers with.
 *
 * @see docs/data.md
 */
final class SaleCreditNotesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<SaleCreditNotePartialData>|Optional $CreditNotes
     */
    public function __construct(
        public string|Optional $SaleID,
        #[DataCollectionOf(SaleCreditNotePartialData::class)]
        public array|Optional $CreditNotes,
    ) {
    }
}
