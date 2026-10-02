<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

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
        public ?string $Name = null,
        public ?string $Comment = null,
        public ?float $Quantity = null,
        public ?float $Price = null,
        public ?float $Discount = null,
        public ?float $Tax = null,
        public ?string $TaxRuleName = null,
        public ?string $AccountCode = null,
        public ?float $Total = null,
    ) {
    }
}
