<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Inventory Write-Off Line Model, a line of an inventory write-off's `Lines`: the `Quantity` of a
 * product to write off, found by `ProductID` or `ProductCode`. `ExpenseAccount` and `Cost` are
 * required for a service product, which the line cannot tell, so they stay optional. `TotalCost` is
 * read-only.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffLineData extends Data
{
    public function __construct(
        public float $Quantity,
        #[RequiredWithout('ProductCode')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(256)]
        public ?string $ProductCode = null,
        #[Max(256)]
        public ?string $Name = null,
        #[Uuid]
        public ?string $BinID = null,
        #[Max(256)]
        public ?string $Bin = null,
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?string $ExpenseAccount = null,
        public ?float $Cost = null,
        public ?float $TotalCost = null,
    ) {
    }
}
