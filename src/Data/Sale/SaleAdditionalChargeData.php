<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Additional Charge Model, a quote or order charge line.
 *
 * @see docs/data.md
 */
final class SaleAdditionalChargeData extends Data
{
    public function __construct(
        public string|Optional $Description,
        public float|Optional $Price,
        public float|Optional $Quantity,
        public float|Optional $Discount,
        public float|Optional $Tax,
        public float|Optional $Total,
        public string|Optional $TaxRule,
        public string|Optional $Comment,
    ) {
    }
}
