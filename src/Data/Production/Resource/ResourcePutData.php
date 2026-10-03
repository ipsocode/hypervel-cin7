<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
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
final class ResourcePutData extends Data
{
    /**
     * @param null|list<ResourceCapacityData> $ResourceCapacities
     * @param null|list<ResourceCostData> $ResourceCosts
     * @param null|list<ResourceRemarkData> $ResourceRemarks
     * @param null|list<ResourceAttachmentData> $ResourceAttachments
     */
    public function __construct(
        public ResourceType $ResourceType,
        public int $CycleDuration,
        #[RequiredWithout('Code')]
        #[Uuid]
        public ?string $ResourceID = null,
        #[RequiredWithout('ResourceID')]
        #[Max(50)]
        public ?string $Code = null,
        #[Max(512)]
        public ?string $Name = null,
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
