<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Sale Manual Journal Model.
 *
 * @see docs/data.md
 */
final class SaleManualJournalData extends Data
{
    /**
     * @param null|list<SaleManualJournalLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(SaleManualJournalLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
