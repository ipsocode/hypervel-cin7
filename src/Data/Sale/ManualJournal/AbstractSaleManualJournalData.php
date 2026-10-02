<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Sale Manual Journal Model and the Sale Manual Journal table share: a sale's
 * `ManualJournals`, the response of `sale/manualJournal` and the body of its POST. Each is a
 * final child that adds its `SaleID`, or none.
 *
 * Every manual journal needs its `Status`, so each child passes it to this constructor; `Lines` is
 * set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleManualJournalData extends Data
{
    /**
     * @var null|list<SaleManualJournalLineData>
     */
    #[DataCollectionOf(SaleManualJournalLineData::class)]
    public ?array $Lines = null;

    public function __construct(
        public TaskStatus $Status,
    ) {
    }
}
