<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\TaskCategory;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Task Category, one entry of a response of its endpoint: the table with its `ID`, which every
 * response sends. The bodies of POST and PUT are `TaskCategoryPostData` and `TaskCategoryPutData`.
 *
 * @see docs/data.md
 */
final class TaskCategoryData extends AbstractTaskCategoryData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
