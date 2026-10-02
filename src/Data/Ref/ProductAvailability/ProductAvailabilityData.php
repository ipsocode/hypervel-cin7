<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\ProductAvailability;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Product Availability, one entry of `ProductAvailabilityList`: a product's stock at one location,
 * bin and batch.
 *
 * @see docs/data.md
 */
final class ProductAvailabilityData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(256)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $Barcode = null,
        #[Max(256)]
        public ?string $Location = null,
        #[Max(50)]
        public ?string $Bin = null,
        public ?string $Batch = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        public ?float $OnHand = null,
        public ?float $Allocated = null,
        public ?float $Available = null,
        public ?float $OnOrder = null,
        public ?float $StockOnHand = null,
        public ?float $InTransit = null,
        #[DateTime]
        public ?string $NextDeliveryDate = null,
    ) {
    }
}
