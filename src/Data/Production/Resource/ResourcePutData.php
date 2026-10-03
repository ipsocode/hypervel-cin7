<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ResourceType;

/**
 * The body of `production/resource` PUT: the Resource table, which requires the `ResourceType`, the
 * `CycleDuration` and the `ResourceID`, or the `Code` when there is no ID. The POST body is
 * `ResourcePostData`.
 *
 * @see docs/data.md
 */
final class ResourcePutData extends AbstractResourceData
{
    public function __construct(
        ResourceType $ResourceType,
        int $CycleDuration,
        #[RequiredWithout('Code')]
        #[Uuid]
        public ?string $ResourceID = null,
        #[RequiredWithout('ResourceID')]
        #[Max(50)]
        public ?string $Code = null,
        #[Max(512)]
        public ?string $Name = null,
    ) {
        parent::__construct($ResourceType, $CycleDuration);
    }
}
