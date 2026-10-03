<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Disassembly Order Line Model, a component line of a disassembly order: a product, found by
 * `ProductID` or `ProductCode`, with its `Quantity` and `Cost`. `Name` and `Unit` are read-only.
 *
 * @see docs/data.md
 */
final class DisassemblyOrderLineData extends Data
{
    public function __construct(
        public float $Quantity,
        public float $Cost,
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
        public ?string $Unit = null,
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?string $Account = null,
    ) {
    }
}
