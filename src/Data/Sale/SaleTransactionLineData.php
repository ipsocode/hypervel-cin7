<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Transaction Line Model.
 *
 * @see docs/data.md
 */
final class SaleTransactionLineData extends Data
{
    public function __construct(
        public ?string $TaskID = null,
        public ?string $TransactionID = null,
        public ?string $Debit = null,
        public ?string $Credit = null,
        public ?string $Description = null,
        public ?float $Amount = null,
        public ?string $EffectiveDate = null,
    ) {
    }
}
