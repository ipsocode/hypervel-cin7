<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Manual Journal Model, a purchase's `ManualJournals`, and the Available field for
 * Purchase Manual Journal table, the response of `purchase/manualJournal`, which adds the
 * purchase's `TaskID`. One name, so one class: `TaskID` is optional, as only the second table has
 * it. The POST body is `PurchaseManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class PurchaseManualJournalData extends AbstractPurchaseManualJournalData implements WithResponse
{
    use HasResponse;

    public function __construct(
        TaskStatus $Status,
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($Status);
    }
}
