<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ResourceType;

/**
 * The fields of the Resource table that `production/resource`'s response and its POST and PUT
 * bodies share, optional in each. The three require a `ResourceType` and a `CycleDuration`, which
 * the constructor takes; each is a final child that adds the `Name`, `ResourceID` and `Code` rules
 * its verb has.
 *
 * @see docs/data.md
 */
abstract class AbstractResourceData extends Data
{
    #[Max(2000)]
    public ?string $Tags = null;

    public ?bool $IsActive = null;

    public ?bool $IsInfinite = null;

    public ?bool $IsAllowAllCapacityUsage = null;

    public ?bool $IsAvailableOnHolidays = null;

    public ?bool $IsAvailableOnWeekends = null;

    /**
     * @var null|list<ResourceCapacityData>
     */
    #[DataCollectionOf(ResourceCapacityData::class)]
    public ?array $ResourceCapacities = null;

    /**
     * @var null|list<ResourceCostData>
     */
    #[DataCollectionOf(ResourceCostData::class)]
    public ?array $ResourceCosts = null;

    /**
     * @var null|list<ResourceRemarkData>
     */
    #[DataCollectionOf(ResourceRemarkData::class)]
    public ?array $ResourceRemarks = null;

    /**
     * @var null|list<ResourceAttachmentData>
     */
    #[DataCollectionOf(ResourceAttachmentData::class)]
    public ?array $ResourceAttachments = null;

    public function __construct(
        public ResourceType $ResourceType,
        public int $CycleDuration,
    ) {
    }
}
