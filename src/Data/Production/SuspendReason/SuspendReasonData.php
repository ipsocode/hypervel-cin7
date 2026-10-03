<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\SuspendReason;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * SuspendReason, a reason an operation can be suspended for, with the `Workcenters` (IDs) it
 * applies to. PUT adds a reason when `SuspendReasonID` is empty and changes it otherwise; `Reason`
 * is for a new one.
 *
 * @see docs/data.md
 */
final class SuspendReasonData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<mixed> $Workcenters
     */
    public function __construct(
        #[Uuid]
        public ?string $SuspendReasonID = null,
        public ?string $Reason = null,
        public ?array $Workcenters = null,
    ) {
    }
}
