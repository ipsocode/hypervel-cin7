<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasCustomFields;
use Ipsocode\Cin7\Concerns\HasProductionProductFields;

/**
 * ProductionRunOutput, a finished product of a run, with the quantity and wastage received. The
 * reference titles its table "Production Run Pending Output", copied from the table above it.
 * `ExpiryDate` is required for a product costed `FEBATCH` or `FESN`; the product's name, unit,
 * costing method and `UnitCost` are read-only. The table requires `Received`, and every response
 * sends it, but the operation-complete request example leaves it out of three of its four finished
 * products, so it is nullable and `#[Required]`, which only a write body checks.
 *
 * @see docs/data.md
 */
final class ProductionRunOutputData extends Data
{
    use HasCustomFields;
    use HasProductionProductFields;

    public function __construct(
        public float $Quantity,
        public float $WastageQuantity,
        #[DateTime]
        public string $ReceivedDate,
        public ?string $CostingMethod = null,
        public ?float $UnitCost = null,
        #[Required]
        public ?bool $Received = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
    ) {
    }
}
