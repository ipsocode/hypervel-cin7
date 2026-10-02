<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;

/**
 * Product Movement Model, one entry of a product's `Movements` (responses only).
 *
 * The reference's table types `BatchSN` as `Decimal`, but its example has a string, and a batch or serial number is not a quantity, so it is a nullable string.
 *
 * @see docs/data.md
 */
final class ProductMovementData extends Data
{
    public function __construct(
        public ?string $TaskID = null,
        public ?string $Type = null,
        public ?string $Date = null,
        public ?string $Number = null,
        public ?int $Status = null,
        public ?float $Quantity = null,
        public ?float $Amount = null,
        public ?string $Location = null,
        public ?string $BatchSN = null,
        public ?string $ExpiryDate = null,
        public ?string $FromTo = null,
    ) {
    }
}
