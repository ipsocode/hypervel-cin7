<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\ManualJournal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Purchase Manual Journal Model and the Available field for Purchase Manual Journal
 * table share: a purchase's `ManualJournals`, the response of `purchase/manualJournal` and the body
 * of its POST. Each is a final child that adds its `TaskID`, or none.
 *
 * Every manual journal needs its `Status`, so each child passes it to this constructor; `Lines` is
 * set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseManualJournalData extends Data
{
    /**
     * @var null|list<PurchaseManualJournalLineData>
     */
    #[DataCollectionOf(PurchaseManualJournalLineData::class)]
    public ?array $Lines = null;

    public function __construct(
        public TaskStatus $Status,
    ) {
    }
}
