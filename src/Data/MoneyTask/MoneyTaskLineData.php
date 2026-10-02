<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTask;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * Money Task Line Model.
 *
 * The reference's table names the last two keys `TaxRule` and `Account`, but every example
 * sends `TaxRuleName` and `AccountCode`; those are the wire keys.
 *
 * @see docs/data.md
 */
final class MoneyTaskLineData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Name,
        public float $Quantity,
        #[Max(50)]
        public string $TaxRuleName,
        #[Max(50)]
        public string $AccountCode,
        public float $Total,
        #[Max(256)]
        public ?string $Comment = null,
        public ?float $Price = null,
        public ?float $Discount = null,
        public ?float $Tax = null,
    ) {
    }
}
