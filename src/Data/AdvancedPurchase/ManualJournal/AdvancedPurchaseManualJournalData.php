<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseManualJournalData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced Purchase Manual Journal Model, one manual journal of an advanced purchase's
 * `ManualJournals`, keyed by the `TaskID` of the purchase invoice task it belongs to. It requires
 * `TaskID` and `Status`, as the Advanced purchase manual journal partial model does; its `Lines`
 * are the purchase's `PurchaseManualJournalLineData`. The `advanced-purchase` examples also send
 * `InvoicingAndReceivingNumber`, which the model does not list; it is modelled, optional.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseManualJournalData extends AbstractPurchaseManualJournalData
{
    public function __construct(
        TaskStatus $Status,
        #[Uuid]
        public string $TaskID,
        public ?int $InvoicingAndReceivingNumber = null,
    ) {
        parent::__construct($Status);
    }
}
