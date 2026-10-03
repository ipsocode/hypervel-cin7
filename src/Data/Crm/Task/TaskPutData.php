<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Task;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskEntityType;

/**
 * The body of PUT: the table with the `ID` of the record to change, which PUT requires. The POST
 * body is `TaskPostData`.
 *
 * @see docs/data.md
 */
final class TaskPutData extends AbstractTaskData
{
    public function __construct(
        string $Name,
        string $StartDate,
        string $EndDate,
        TaskEntityType $EntityType,
        string $EntityID,
        string $TaskStatus,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name, $StartDate, $EndDate, $EntityType, $EntityID, $TaskStatus);
    }
}
