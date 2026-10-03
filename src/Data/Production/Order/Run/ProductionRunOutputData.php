<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOutput, a finished product of a run, with the quantity and wastage received. The
 * reference titles its table "Production Run Pending Output", copied from the table above it.
 * `ExpiryDate` is required for a product costed `FEBATCH` or `FESN`; the product's name, unit,
 * costing method and `UnitCost` are read-only.
 *
 * @see docs/data.md
 */
final class ProductionRunOutputData extends Data
{
    public function __construct(
        public float $Quantity,
        public float $WastageQuantity,
        #[DateTime]
        public string $ReceivedDate,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?string $Unit = null,
        public ?string $CostingMethod = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?float $UnitCost = null,
        public ?bool $Received = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?string $CustomField1 = null,
        public ?string $CustomField2 = null,
        public ?string $CustomField3 = null,
        public ?string $CustomField4 = null,
        public ?string $CustomField5 = null,
        public ?string $CustomField6 = null,
        public ?string $CustomField7 = null,
        public ?string $CustomField8 = null,
        public ?string $CustomField9 = null,
        public ?string $CustomField10 = null,
    ) {
    }
}
