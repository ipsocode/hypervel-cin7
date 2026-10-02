<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

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
        public ?string $Status = null,
        public ?string $RequireBy = null,
        public ?SaleShippingAddressData $ShippingAddress = null,
        public ?string $ShippingNotes = null,
        #[DataCollectionOf(SaleFulfilmentShipLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
