<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Transaction Line Model.
 *
 * @see docs/data.md
 */
final class SaleTransactionLineData extends Data
{
    public function __construct(
        public string|Optional $TaskID,
        public string|Optional $TransactionID,
        public string|Optional $Debit,
        public string|Optional $Credit,
        public string|Optional $Description,
        public float|Optional $Amount,
        public string|Optional $EffectiveDate,
    ) {
    }
}
