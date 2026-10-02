<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Reference;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\ShipZonesEnabled\ShipZonesEnabledData;
use Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled\GetShipZonesEnabled;
use Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled\PutShipZonesEnabled;

/**
 * `reference/shipZonesEnabled`, whether shipping zones are enabled.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ShipZonesEnabledResource extends BaseResource
{
    /**
     * Whether shipping zones are enabled.
     */
    public function get(): Response
    {
        return $this->connector->send(new GetShipZonesEnabled);
    }

    /**
     * Enable or disable shipping zones.
     *
     * @param array<string, mixed>|ShipZonesEnabledData $body
     */
    public function put(array|ShipZonesEnabledData $body): Response
    {
        return $this->connector->send(new PutShipZonesEnabled($body));
    }
}
