<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Opportunity;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Opportunity Line, one entry of an opportunity's `Lines`: a product, by `ProductID` or `ProductSku` (the examples write the key `ProductSku`, the table `ProductSKU`), its quantity, price, tax and total.
 *
 * @see docs/data.md
 */
final class OpportunityLineData extends Data
{
    public function __construct(
        public float $Quantity,
        public float $Price,
        public float $Tax,
        public float $Total,
        #[Uuid]
        public ?string $ID = null,
        #[RequiredWithout('ProductSku')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(50)]
        public ?string $ProductSku = null,
        #[Max(256)]
        public ?string $ProductName = null,
        public ?float $Discount = null,
        #[Max(255)]
        public ?string $TaxRule = null,
        public ?float $TaxPercent = null,
        #[Max(1024)]
        public ?string $Comment = null,
    ) {
    }
}
