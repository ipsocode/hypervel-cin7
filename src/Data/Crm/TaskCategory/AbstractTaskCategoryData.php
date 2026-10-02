<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\TaskCategory;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the Task Category table: the response of its endpoint and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every category needs its `Name`, so each child passes it to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractTaskCategoryData extends Data
{
    #[Max(6)]
    public ?string $Color = null;

    #[Max(6)]
    public ?string $BackgroundColor = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
    ) {
    }
}
