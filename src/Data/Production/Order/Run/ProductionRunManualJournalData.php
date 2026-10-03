<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunManualJournal, a manual journal of a run. `JournalID` is required when updating;
 * `OperationName` is read-only.
 *
 * @see docs/data.md
 */
final class ProductionRunManualJournalData extends Data
{
    public function __construct(
        public float $Amount,
        #[DateTime]
        public string $Date,
        #[Max(50)]
        public string $Debit,
        #[Max(50)]
        public string $Credit,
        #[Uuid]
        public ?string $JournalID = null,
        #[Max(50)]
        public ?string $Reference = null,
        #[Uuid]
        public ?string $OperationID = null,
        public ?string $OperationName = null,
    ) {
    }
}
