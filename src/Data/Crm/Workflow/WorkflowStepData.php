<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Workflow;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\WorkflowSkipHoliday;

/**
 * Workflow Step, one entry of a workflow's `Steps`: a task to create, with its name and what to do on a holiday. The table's `ID` is in responses only, so it is optional.
 *
 * @see docs/data.md
 */
final class WorkflowStepData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Name,
        public WorkflowSkipHoliday $SkipHoliday,
        #[Uuid]
        public ?string $ID = null,
        #[Max(256)]
        public ?string $Category = null,
        public ?int $Days = null,
        #[Max(8)]
        public ?string $StartTime = null,
        #[Max(8)]
        public ?string $EndTime = null,
        #[Max(256)]
        public ?string $UserName = null,
    ) {
    }
}
