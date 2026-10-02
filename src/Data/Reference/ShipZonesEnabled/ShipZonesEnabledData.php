<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\ShipZonesEnabled;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Whether shipping zones are enabled: the response of `reference/shipZonesEnabled` GET and PUT, and
 * the body of its PUT. The reference has no table for it, only the examples.
 *
 * @see docs/data.md
 */
final class ShipZonesEnabledData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public bool $IsEnabled,
    ) {
    }
}
