<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Sale Manual Journal Line Model.
 *
 * @see docs/data.md
 */
final class SaleManualJournalLineData extends Data
{
    public function __construct(
        public float $Amount,
        #[DateTime]
        public string $Date,
        public string $Debit,
        public string $Credit,
        public ?string $Reference = null,
    ) {
    }
}
