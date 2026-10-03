<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\OrderList;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderListSourceTask, a sale task that produced the production order: `SourceTaskType`
 * is `0` for a simple task and `1` for a master.
 *
 * @see docs/data.md
 */
final class ProductionOrderListSourceTaskData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $SourceTaskID = null,
        public ?string $SourceTaskNumber = null,
        public ?int $SourceTaskType = null,
        public ?bool $IsSourceTaskVoided = null,
    ) {
    }
}
