<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Manual Journal Model, one of a sale's `ManualJournals`, and the Sale Manual Journal table,
 * the response of `sale/manualJournal`, which adds the `SaleID`. One name, so one class: `SaleID`
 * is optional, as only the second table has it. The POST body is `SaleManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class SaleManualJournalData extends AbstractSaleManualJournalData implements WithResponse
{
    use HasResponse;

    public function __construct(
        TaskStatus $Status,
        #[Uuid]
        public ?string $SaleID = null,
    ) {
        parent::__construct($Status);
    }
}
