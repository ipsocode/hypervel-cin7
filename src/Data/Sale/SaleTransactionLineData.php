<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

/**
 * Sale Transaction Line Model.
 *
 * @see docs/data.md
 */
final class SaleTransactionLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[Uuid]
        public ?string $TransactionID = null,
        public ?string $Debit = null,
        public ?string $Credit = null,
        public ?string $Description = null,
        public ?float $Amount = null,
        #[DateTime]
        public ?string $EffectiveDate = null,
    ) {
    }
}
