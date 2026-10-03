<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOperationProduct, a read-only input, output or finished product of a run operation.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationProductData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?string $Unit = null,
        public ?float $OutputQuantity = null,
        public ?float $ExpectedQuantity = null,
        public ?float $WastageQuantity = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
    ) {
    }
}
