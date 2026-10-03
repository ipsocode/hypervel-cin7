<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Manual Journal Model, one of a sale's `ManualJournals`, and the Sale Manual Journal table,
 * the response of `sale/manualJournal`, which adds the `SaleID`. One name, so one class: the table
 * requires `SaleID` and a sale's embedded `ManualJournals` have none, so it is nullable and
 * `#[Required]`, which only a write body checks. The POST body is `SaleManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class SaleManualJournalData extends AbstractSaleManualJournalData implements WithResponse
{
    use HasResponse;

    public function __construct(
        TaskStatus $Status,
        #[Required]
        #[Uuid]
        public ?string $SaleID = null,
    ) {
        parent::__construct($Status);
    }
}
