<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ProductionRunCoManTaskType;

/**
 * ProductionRunOperationCoManTask, a read-only task started by a co-manufacturing operation.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationCoManTaskData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?ProductionRunCoManTaskType $Type = null,
        public ?string $TaskStatus = null,
        public ?string $TaskNumber = null,
    ) {
    }
}
