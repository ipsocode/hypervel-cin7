<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Manual Journal Line Model.
 *
 * @see docs/data.md
 */
final class SaleManualJournalLineData extends Data
{
    public function __construct(
        public string|Optional $Reference,
        public float|Optional $Amount,
        public string|Optional $Date,
        public string|Optional $Debit,
        public string|Optional $Credit,
    ) {
    }
}
