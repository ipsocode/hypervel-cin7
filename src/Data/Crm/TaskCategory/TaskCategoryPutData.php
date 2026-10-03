<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\TaskCategory;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of PUT: the table with the `ID` of the record to change, which PUT requires. The POST
 * body is `TaskCategoryPostData`.
 *
 * @see docs/data.md
 */
final class TaskCategoryPutData extends AbstractTaskCategoryData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
