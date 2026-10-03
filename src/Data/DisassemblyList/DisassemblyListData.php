<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\DisassemblyList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\DisassemblyStatus;

/**
 * Disassembly List, one entry of `Disassemblies`.
 *
 * @see docs/data.md
 */
final class DisassemblyListData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $DisassemblyNumber = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?float $Quantity = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $Location = null,
        #[DateTime]
        public ?string $Date = null,
        public ?DisassemblyStatus $Status = null,
    ) {
    }
}
