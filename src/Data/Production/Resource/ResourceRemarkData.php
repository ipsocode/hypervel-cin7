<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ResourceRemark, a remark on a resource. `RemarkID` and `CreatedDate` are read-only.
 *
 * @see docs/data.md
 */
final class ResourceRemarkData extends Data
{
    public function __construct(
        #[Max(1024)]
        public string $Remark,
        #[Uuid]
        public ?string $RemarkID = null,
        #[DateTime]
        public ?string $CreatedDate = null,
    ) {
    }
}
