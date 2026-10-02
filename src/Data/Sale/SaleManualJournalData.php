<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Manual Journal Model.
 *
 * @see docs/data.md
 */
final class SaleManualJournalData extends Data
{
    /**
     * @param list<SaleManualJournalLineData>|Optional $Lines
     */
    public function __construct(
        public string|Optional $Status,
        #[DataCollectionOf(SaleManualJournalLineData::class)]
        public array|Optional $Lines,
    ) {
    }
}
