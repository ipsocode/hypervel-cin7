<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\ResourceType;

/**
 * Resource, a production resource, the response of every `production/resource` action, the rows of
 * `production/resourceList` and the nested resource of a POST: capacities, costs, remarks and
 * attachments. It requires the `ResourceType` and `CycleDuration` its table does, which every
 * response sends; `CycleDuration` is in seconds, 1 to 7776000. The bodies of POST and PUT are
 * `ResourcePostData` and `ResourcePutData`.
 *
 * @see docs/data.md
 */
final class ResourceData extends AbstractResourceData implements WithResponse
{
    use HasResponse;

    public function __construct(
        ResourceType $ResourceType,
        int $CycleDuration,
        #[Uuid]
        public ?string $ResourceID = null,
        #[Max(50)]
        public ?string $Code = null,
        #[Max(512)]
        public ?string $Name = null,
    ) {
        parent::__construct($ResourceType, $CycleDuration);
    }
}
