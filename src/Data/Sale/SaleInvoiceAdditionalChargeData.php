<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Invoice Additional Charge Model, also used by credit notes.
 *
 * @see docs/data.md
 */
final class SaleInvoiceAdditionalChargeData extends Data
{
    public function __construct(
        public ?string $Description = null,
        public ?float $Quantity = null,
        public ?float $Price = null,
        public ?float $Discount = null,
        public ?float $Tax = null,
        public ?float $Total = null,
        public ?string $TaxRule = null,
        public ?string $Account = null,
        public ?string $Comment = null,
    ) {
    }
}
