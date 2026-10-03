<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\ResourceType;

/**
 * Resource, a production resource, the response of every `production/resource` action, the rows of
 * `production/resourceList` and the nested resource of a POST: capacities, costs, remarks and
 * attachments. `CycleDuration` is in seconds, 1 to 7776000. The bodies of POST and PUT are
 * `ResourcePostData` and `ResourcePutData`.
 *
 * @see docs/data.md
 */
final class ResourceData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ResourceCapacityData> $ResourceCapacities
     * @param null|list<ResourceCostData> $ResourceCosts
     * @param null|list<ResourceRemarkData> $ResourceRemarks
     * @param null|list<ResourceAttachmentData> $ResourceAttachments
     */
    public function __construct(
        #[Uuid]
        public ?string $ResourceID = null,
        #[Max(50)]
        public ?string $Code = null,
        #[Max(512)]
        public ?string $Name = null,
        public ?ResourceType $ResourceType = null,
        public ?int $CycleDuration = null,
        #[Max(2000)]
        public ?string $Tags = null,
        public ?bool $IsActive = null,
        public ?bool $IsInfinite = null,
        public ?bool $IsAllowAllCapacityUsage = null,
        public ?bool $IsAvailableOnHolidays = null,
        public ?bool $IsAvailableOnWeekends = null,
        #[DataCollectionOf(ResourceCapacityData::class)]
        public ?array $ResourceCapacities = null,
        #[DataCollectionOf(ResourceCostData::class)]
        public ?array $ResourceCosts = null,
        #[DataCollectionOf(ResourceRemarkData::class)]
        public ?array $ResourceRemarks = null,
        #[DataCollectionOf(ResourceAttachmentData::class)]
        public ?array $ResourceAttachments = null,
    ) {
    }
}
