<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Opportunity;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Opportunity Additional Charge, one entry of an opportunity's `AdditionalCharges`: a service, by
 * description, with its quantity, amount, tax and total. The table requires the `ID`, but a charge
 * being added to an opportunity has none yet, so it stays optional.
 *
 * @see docs/data.md
 */
final class OpportunityAdditionalChargeData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Description,
        public float $Quantity,
        public float $Amount,
        public float $Tax,
        public float $Total,
        #[Uuid]
        public ?string $ID = null,
        public ?float $Discount = null,
        #[Max(255)]
        public ?string $TaxRule = null,
        public ?float $TaxPercent = null,
        #[Max(1024)]
        public ?string $Comment = null,
    ) {
    }
}
