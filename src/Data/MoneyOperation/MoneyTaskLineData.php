<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
        public string|Optional $Name,
        public string|Optional $Comment,
        public float|Optional $Quantity,
        public float|Optional $Price,
        public float|Optional $Discount,
        public float|Optional $Tax,
        public string|Optional $TaxRuleName,
        public string|Optional $AccountCode,
        public float|Optional $Total,
    ) {
    }
}
