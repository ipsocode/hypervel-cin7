<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
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
final class ResourcePostData extends Data
{
    /**
     * @param null|list<ResourceCapacityData> $ResourceCapacities
     * @param null|list<ResourceCostData> $ResourceCosts
     * @param null|list<ResourceRemarkData> $ResourceRemarks
     * @param null|list<ResourceAttachmentData> $ResourceAttachments
     */
    public function __construct(
        #[Max(512)]
        public string $Name,
        public ResourceType $ResourceType,
        public int $CycleDuration,
        #[Max(50)]
        public ?string $Code = null,
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
