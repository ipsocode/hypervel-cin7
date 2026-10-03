<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ResourceType;

/**
 * A resource of the body of `production/resource` POST: the Resource table, which requires a
 * `Name`, a `ResourceType` and a `CycleDuration`; `ResourceID` is ignored for POST. The PUT body
 * is `ResourcePutData`.
 *
 * @see docs/data.md
 */
final class ResourcePostData extends AbstractResourceData
{
    public function __construct(
        ResourceType $ResourceType,
        int $CycleDuration,
        #[Max(512)]
        public string $Name,
        #[Max(50)]
        public ?string $Code = null,
    ) {
        parent::__construct($ResourceType, $CycleDuration);
    }
}
