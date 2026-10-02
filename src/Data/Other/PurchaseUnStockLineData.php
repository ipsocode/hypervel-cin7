<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;

/**
 * Purchase Unstock Line Model, a line of a purchase credit note's `Unstock`: the stock batch
 * (`CardID`) and `Quantity` it takes back out of stock. The simple and advanced purchases' credit
 * notes share it. `ProductID`, `SKU`, `Name`, `Location`, `BatchSN` and `ExpiryDate` are
 * read-only, so the write requests leave them out of the body. Like every object with a
 * `ProductID`, it carries the product fields.
 *
 * @see docs/data.md
 */
final class PurchaseUnStockLineData extends Data
{
    use HasProductFields;

    public function __construct(
        #[Uuid]
        public string $CardID,
        public float $Quantity,
        #[DateTime]
        public ?string $Date = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $Location = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
    ) {
    }
}
