<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Disassembly Pick Line Model, a line of a disassembly's pick: the stock taken from the product
 * being disassembled. Every field is read-only.
 *
 * @see docs/data.md
 */
final class DisassemblyPickLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $BinID = null,
        #[Max(256)]
        public ?string $Bin = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?float $Available = null,
        public ?float $UnitCost = null,
        public ?float $Quantity = null,
        public ?float $TotalCost = null,
    ) {
    }
}
