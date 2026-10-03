<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\WorkCenters;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\CoManProcurementType;

/**
 * WorkCenter, a production work center. `SupplierID` is required for a co-manufacturing work
 * center, `CoManProcurementType` for a co-man one (`Transfer`, `Buysell` or `Purchase`) and
 * `WorkCenterSuppliers` for a purchasing one that is not co-man, which `#[RequiredIf]` checks for
 * the first and the documented notes leave to the caller for the others.
 *
 * @see docs/data.md
 */
final class WorkCenterData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<WorkCenterLocationData> $WorkCenterLocations
     * @param null|list<WorkCenterSupplierData> $WorkCenterSuppliers
     */
    public function __construct(
        #[Uuid]
        public string $WorkCenterID,
        #[Max(256)]
        public string $Code,
        #[Max(256)]
        public string $Name,
        public bool $IsActive,
        public bool $IsCoMan,
        public bool $IsCoManPurchase,
        #[RequiredIf('IsCoMan', true)]
        #[Uuid]
        public ?string $SupplierID = null,
        public ?string $SupplierName = null,
        public ?CoManProcurementType $CoManProcurementType = null,
        #[DataCollectionOf(WorkCenterLocationData::class)]
        public ?array $WorkCenterLocations = null,
        #[DataCollectionOf(WorkCenterSupplierData::class)]
        public ?array $WorkCenterSuppliers = null,
    ) {
    }
}
