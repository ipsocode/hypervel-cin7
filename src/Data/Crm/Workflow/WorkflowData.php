<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Workflow;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Enums\WorkflowDueDaysType;

/**
 * Workflow, one entry of a response of its endpoint: the table with its `ID`, which every response
 * sends. The bodies of POST and PUT are `WorkflowPostData` and `WorkflowPutData`.
 *
 * @see docs/data.md
 */
final class WorkflowData extends AbstractWorkflowData implements WithResponse
{
    use HasResponse;

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
