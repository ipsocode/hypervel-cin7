<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Concerns;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The product a production run's component, output, pending output and co-manufacturing line
 * names: `ProductID`, `ProductCode`, `ProductName`, `Unit`, `BatchSN` and `ExpiryDate`, which the
 * four tables document alike and which every response may leave out.
 *
 * @see docs/data.md
 */
trait HasProductionProductFields
{
    #[Uuid]
    public ?string $ProductID = null;

    #[Max(50)]
    public ?string $ProductCode = null;

    public ?string $ProductName = null;

    public ?string $Unit = null;

    #[Max(50)]
    public ?string $BatchSN = null;

    #[DateTime]
    public ?string $ExpiryDate = null;
}
