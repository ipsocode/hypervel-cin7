<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

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
        #[Uuid]
        public ?string $TaskID = null,
        #[Max(256)]
        public ?string $Type = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $Number = null,
        public ?int $Status = null,
        public ?float $Quantity = null,
        public ?float $Amount = null,
        public ?string $Location = null,
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?string $FromTo = null,
    ) {
    }
}
