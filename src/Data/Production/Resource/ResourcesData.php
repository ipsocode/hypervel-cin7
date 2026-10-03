<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The resources a `production/resource` POST answers with, under `Resources`; the rows of
 * `production/resourceList` sit under the same key.
 *
 * @see docs/data.md
 */
final class ResourcesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ResourceData> $Resources
     */
    public function __construct(
        #[DataCollectionOf(ResourceData::class)]
        public ?array $Resources = null,
    ) {
    }
}
