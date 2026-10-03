<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ResourceUnit, one unit of a resource at a location. For a `Labor` resource its `Name` is the
 * email of a registered user.
 *
 * @see docs/data.md
 */
final class ResourceUnitData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Name,
        #[DateTime]
        public ?string $NonOperationalFrom = null,
        #[DateTime]
        public ?string $NonOperationalTo = null,
        #[DateTime]
        public ?string $OperationalFrom = null,
        #[DateTime]
        public ?string $OperationalTo = null,
    ) {
    }
}
