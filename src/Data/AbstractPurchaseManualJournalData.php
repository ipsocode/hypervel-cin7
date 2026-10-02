<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Purchase Manual Journal Model, the Available field for Purchase Manual Journal
 * table and the Advanced purchase manual journal partial model share: a purchase's
 * `ManualJournals`, the response of `purchase/manualJournal` and the body of its POST, and an
 * advanced purchase's manual journal, as `advanced-purchase/manualJournal` answers it and its POST
 * takes it. Each is a final child that adds its `TaskID`, or none, and the advanced purchase's POST
 * body its `PurchaseID`.
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
