<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/run/manualJournal` PUT: the `RunID` and its `ManualJournals`.
 *
 * @see docs/data.md
 */
final class ProductionRunManualJournalsPutData extends Data
{
    /**
     * @param list<ProductionRunManualJournalData> $ManualJournals
     */
    public function __construct(
        #[Uuid]
        public string $RunID,
        #[DataCollectionOf(ProductionRunManualJournalData::class)]
        public array $ManualJournals,
    ) {
    }
}
