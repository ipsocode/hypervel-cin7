<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Fulfilment Ship Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipData extends Data
{
    /**
     * @param list<SaleFulfilmentShipLineData>|Optional $Lines
     */
    public function __construct(
        public string|Optional $Status,
        public string|Optional|null $RequireBy,
        public SaleShippingAddressData|Optional $ShippingAddress,
        public string|Optional $ShippingNotes,
        #[DataCollectionOf(SaleFulfilmentShipLineData::class)]
        public array|Optional $Lines,
    ) {
    }
}
