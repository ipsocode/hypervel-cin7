<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Disassembly Order Service Line Model, a service line of a disassembly order: a service product,
 * found by `ProductID` or `Name`, with its expense `Account` and `Amount`.
 *
 * @see docs/data.md
 */
final class DisassemblyOrderServiceLineData extends Data
{
    public function __construct(
        public string $Account,
        public float $Amount,
        #[RequiredWithout('Name')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(256)]
        public ?string $Name = null,
        public ?string $Comments = null,
    ) {
    }
}
