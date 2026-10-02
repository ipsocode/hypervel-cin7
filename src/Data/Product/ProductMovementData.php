<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
        public string|Optional $TaskID,
        public string|Optional $Type,
        public string|Optional $Date,
        public string|Optional $Number,
        public int|Optional $Status,
        public float|Optional $Quantity,
        public float|Optional $Amount,
        public string|Optional $Location,
        public string|Optional|null $BatchSN,
        public string|Optional|null $ExpiryDate,
        public string|Optional|null $FromTo,
    ) {
    }
}
