<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\WorkCenters;

use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * WorkCenterSupplier, a supplier of a purchasing work center, by `SupplierID` or `SupplierName`.
 *
 * @see docs/data.md
 */
final class WorkCenterSupplierData extends Data
{
    public function __construct(
        #[RequiredWithout('SupplierName')]
        #[Uuid]
        public ?string $SupplierID = null,
        #[RequiredWithout('SupplierID')]
        public ?string $SupplierName = null,
    ) {
    }
}
