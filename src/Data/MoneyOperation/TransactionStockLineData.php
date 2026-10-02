<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

use Hypervel\Data\Data;

/**
 * Transaction Stock Line Model.
 *
 * @see docs/data.md
 */
final class TransactionStockLineData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $Debit = null,
        public ?string $Credit = null,
        public ?float $Amount = null,
        public ?string $EffectiveDate = null,
    ) {
    }
}
