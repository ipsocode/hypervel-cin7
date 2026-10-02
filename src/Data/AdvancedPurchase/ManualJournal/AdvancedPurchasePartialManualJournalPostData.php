<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `advanced-purchase/manualJournal` POST: the Advanced purchase manual journal partial
 * model with a `Status` of `DRAFT` or `AUTHORISED`, plus the purchase's `PurchaseID`, which the
 * Available field for Purchase Manual Journal table requires and the POST example sends. It can be
 * sent even when the journal is authorised. The response is `AdvancedPurchaseManualJournalsData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialManualJournalPostData extends AbstractPurchaseManualJournalData
{
    public function __construct(
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        #[Uuid]
        public string $PurchaseID,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status);
    }
}
