<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Workflow;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Enums\WorkflowDueDaysType;

/**
 * The body of PUT: the table with the `ID` of the record to change, which PUT requires. The POST
 * body is `WorkflowPostData`.
 *
 * @see docs/data.md
 */
final class WorkflowPutData extends AbstractWorkflowData
{
    public function __construct(
        string $Name,
        TaskEntityType $EntityType,
        WorkflowDueDaysType $DueDaysType,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name, $EntityType, $DueDaysType);
    }
}
