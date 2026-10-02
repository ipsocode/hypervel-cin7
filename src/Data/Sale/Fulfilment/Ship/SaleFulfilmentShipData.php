<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Sale\SaleShippingAddressData;
use Ipsocode\Cin7\Enums\ShipmentStatus;

/**
 * Sale Fulfilment Ship Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipData extends Data
{
    /**
     * @param null|list<SaleFulfilmentShipLineData> $Lines
     */
    public function __construct(
        public ?ShipmentStatus $Status = null,
        #[Date]
        public ?string $RequireBy = null,
        public ?SaleShippingAddressData $ShippingAddress = null,
        #[Max(1024)]
        public ?string $ShippingNotes = null,
        #[DataCollectionOf(SaleFulfilmentShipLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
