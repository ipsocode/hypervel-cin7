<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Task;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\TaskEntityType;

/**
 * The fields of the Task table: the response of its endpoint and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every task needs its name, dates, entity type and id, and status, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractTaskData extends Data
{
    public ?string $Description = null;

    public ?bool $IsAllDay = null;

    #[DateTime]
    public ?string $CompletedDate = null;

    #[Max(256)]
    public ?string $Category = null;

    #[Max(256)]
    public ?string $WorkflowName = null;

    #[Max(256)]
    public ?string $AssignedTo = null;

    public ?bool $IsImportant = null;

    #[Max(256)]
    public ?string $AssignedBy = null;

    #[DateTime]
    public ?string $VoidDate = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
        #[DateTime]
        public string $StartDate,
        #[DateTime]
        public string $EndDate,
        public TaskEntityType $EntityType,
        #[Uuid]
        public string $EntityID,
        #[Max(64)]
        public string $TaskStatus,
    ) {
    }
}
