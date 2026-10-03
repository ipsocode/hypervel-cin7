<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Transaction Stock Line Model.
 *
 * @see docs/data.md
 */
final class TransactionStockLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        public ?string $Debit = null,
        public ?string $Credit = null,
        public ?float $Amount = null,
        #[DateTime]
        public ?string $EffectiveDate = null,
    ) {
    }
}
