<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\DisassemblyPostStatus;

/**
 * The body of `disassembly` POST: the table of POST fields, which requires `Status`, `WIPAccount`
 * and `Quantity`, a product by `ProductID` or `ProductCode` and a location by `LocationID` or
 * `Location`. `ProductName` and `CompletionDate` are in the example, not the table. The response
 * is `DisassemblyData`.
 *
 * @see docs/data.md
 */
final class DisassemblyPostData extends Data
{
    public function __construct(
        public DisassemblyPostStatus $Status,
        public string $WIPAccount,
        public float $Quantity,
        #[RequiredWithout('ProductCode')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        #[RequiredWithout('Location')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        public ?string $Location = null,
        #[DateTime]
        public ?string $CompletionDate = null,
    ) {
    }
}
