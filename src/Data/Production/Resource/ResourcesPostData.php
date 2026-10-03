<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * The body of `production/resource` POST: the `Resources` to create.
 *
 * @see docs/data.md
 */
final class ResourcesPostData extends Data
{
    /**
     * @param list<ResourcePostData> $Resources
     */
    public function __construct(
        #[DataCollectionOf(ResourcePostData::class)]
        public array $Resources,
    ) {
    }
}
