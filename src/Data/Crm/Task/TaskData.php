<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Task;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskEntityType;

/**
 * Task, one entry of a response of its endpoint: the table with its `ID`. The bodies of POST
 * and PUT are `TaskPostData` and `TaskPutData`.
 *
 * @see docs/data.md
 */
final class TaskData extends AbstractTaskData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        string $StartDate,
        string $EndDate,
        TaskEntityType $EntityType,
        string $EntityID,
        string $TaskStatus,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Name, $StartDate, $EndDate, $EntityType, $EntityID, $TaskStatus);
    }
}
