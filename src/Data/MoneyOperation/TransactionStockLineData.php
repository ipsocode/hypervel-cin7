<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Transaction Stock Line Model.
 *
 * @see docs/data.md
 */
final class TransactionStockLineData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $Debit,
        public string|Optional $Credit,
        public float|Optional $Amount,
        public string|Optional $EffectiveDate,
    ) {
    }
}
