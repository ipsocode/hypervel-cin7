<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Additional Charge Model, a quote or order charge line.
 *
 * @see docs/data.md
 */
final class SaleAdditionalChargeData extends Data
{
    public function __construct(
        public ?string $Description = null,
        public ?float $Price = null,
        public ?float $Quantity = null,
        public ?float $Discount = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?string $TaxRule = null,
        public ?string $Comment = null,
    ) {
    }
}
