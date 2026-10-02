<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Workflow;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Enums\WorkflowDueDaysType;

/**
 * The fields of the Workflow table: the response of its endpoint and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every workflow needs its name, entity type and due days type, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractWorkflowData extends Data
{
    public ?bool $IsActive = null;

    /**
     * @var null|list<WorkflowStepData>
     */
    #[DataCollectionOf(WorkflowStepData::class)]
    public ?array $Steps = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
        public TaskEntityType $EntityType,
        public WorkflowDueDaysType $DueDaysType,
    ) {
    }
}
