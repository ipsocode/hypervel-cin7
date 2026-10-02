<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced purchase manual journal partial model, one manual journal of an advanced purchase (an
 * item of the `ManualJournals` that `advanced-purchase/manualJournal` answers with), keyed by the
 * `TaskID` of the purchase invoice task it belongs to. It requires `TaskID` and `Status`; its
 * `Lines` are the purchase's `PurchaseManualJournalLineData`. The POST body is
 * `AdvancedPurchasePartialManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePartialManualJournalData extends AbstractPurchaseManualJournalData
{
    public function __construct(
        TaskStatus $Status,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status);
    }
}
