<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The line fields the reference's finished goods pick, inventory write-off and disassembly order
 * tables share: the product (`ProductID` or `ProductCode`, and its `Name`), the bin and the batch. Each
 * requires a `Quantity`, which the constructor takes; each is a final child that adds the `Unit`,
 * `Cost` and account fields its own table has.
 *
 * @see docs/data.md
 */
abstract class AbstractStockLineData extends Data
{
    #[RequiredWithout('ProductCode')]
    #[Uuid]
    public ?string $ProductID = null;

    #[RequiredWithout('ProductID')]
    #[Max(256)]
    public ?string $ProductCode = null;

    #[Max(256)]
    public ?string $Name = null;

    #[Uuid]
    public ?string $BinID = null;

    #[Max(256)]
    public ?string $Bin = null;

    public ?string $BatchSN = null;

    #[DateTime]
    public ?string $ExpiryDate = null;

    public function __construct(
        public float $Quantity,
    ) {
    }
}
