<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\WorkCenters;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The work centers `production/workcenters` answers with, under `Workcenters` (lower-case c), and
 * the `WarningMessage` a PUT adds; also the body of its POST and PUT.
 *
 * @see docs/data.md
 */
final class WorkCentersData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<WorkCenterData> $Workcenters
     */
    public function __construct(
        #[DataCollectionOf(WorkCenterData::class)]
        public ?array $Workcenters = null,
        public ?string $WarningMessage = null,
    ) {
    }
}
