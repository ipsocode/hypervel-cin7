<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderSourceTask, a sale task that produced the production order: read-only.
 *
 * @see docs/data.md
 */
final class ProductionOrderSourceTaskData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $SourceTaskID = null,
        public ?string $SourceTaskNumber = null,
    ) {
    }
}
