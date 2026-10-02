<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

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
        public ?string $Status = null,
        #[DataCollectionOf(SaleManualJournalLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
