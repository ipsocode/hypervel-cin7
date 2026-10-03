<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasProductionProductFields;

/**
 * ProductionRunPendingOutput, a planned finished product of a run: a product by `ProductID`, which
 * has priority, or `ProductCode`. `ExpiryDate` is required for a product costed `FEBATCH` or
 * `FESN`.
 *
 * @see docs/data.md
 */
final class ProductionRunPendingOutputData extends Data
{
    use HasProductionProductFields;

    public function __construct(
        public ?string $CostingMethod = null,
    ) {
    }
}
