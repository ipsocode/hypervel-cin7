<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `purchase/manualJournal` POST: the Available field for Purchase Manual Journal table
 * with the `TaskID` it requires and a `Status` of `DRAFT` or `AUTHORISED`. The response is
 * `PurchaseManualJournalData`.
 *
 * @see docs/data.md
 */
final class PurchaseManualJournalPostData extends AbstractPurchaseManualJournalData
{
    public function __construct(
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status);
    }
}
