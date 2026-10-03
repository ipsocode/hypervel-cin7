<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Manual Journal Model, a purchase's `ManualJournals`, and the Available field for
 * Purchase Manual Journal table, the response of `purchase/manualJournal`, which adds the
 * purchase's `TaskID`. One name, so one class: the table requires `TaskID` and the purchase's
 * `ManualJournals` embed the journal without it, so it is nullable and `#[Required]`. The POST body
 * is `PurchaseManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class PurchaseManualJournalData extends AbstractPurchaseManualJournalData implements WithResponse
{
    use HasResponse;

    public function __construct(
        TaskStatus $Status,
        #[Required]
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($Status);
    }
}
