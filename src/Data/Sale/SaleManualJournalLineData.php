<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Manual Journal Line Model.
 *
 * @see docs/data.md
 */
final class SaleManualJournalLineData extends Data
{
    public function __construct(
        public ?string $Reference = null,
        public ?float $Amount = null,
        public ?string $Date = null,
        public ?string $Debit = null,
        public ?string $Credit = null,
    ) {
    }
}
