<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Resource;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ResourceCapacity, a resource's capacity at a location, by `LocationID`, or by `LocationName`
 * when there is no ID; `ResourceQuantity` is read-only. The table requires `LocationID`, but the
 * POST response and a location named alone say otherwise: it is `#[RequiredWithout]`
 * `LocationName`.
 *
 * @see docs/data.md
 */
final class ResourceCapacityData extends Data
{
    /**
     * @param null|list<CustomWorkingDayData> $CustomWorkingDays
     * @param null|list<ResourceUnitData> $ResourceUnits
     */
    public function __construct(
        #[RequiredWithout('LocationName')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        #[Max(50)]
        public ?string $LocationName = null,
        public ?int $ResourceQuantity = null,
        #[DateTime]
        public ?string $NonOperationalFrom = null,
        #[DateTime]
        public ?string $NonOperationalTo = null,
        #[DataCollectionOf(CustomWorkingDayData::class)]
        public ?array $CustomWorkingDays = null,
        #[DataCollectionOf(ResourceUnitData::class)]
        public ?array $ResourceUnits = null,
    ) {
    }
}
