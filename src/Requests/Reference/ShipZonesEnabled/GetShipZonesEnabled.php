<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Reference\ShipZonesEnabled\ShipZonesEnabledData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET reference/shipZonesEnabled`, whether shipping zones are enabled.
 *
 * @extends Cin7Request<ShipZonesEnabledData>
 */
final class GetShipZonesEnabled extends Cin7Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'reference/shipZonesEnabled';
    }

    public function createDtoFromResponse(Response $response): ShipZonesEnabledData
    {
        return ShipZonesEnabledData::from($response->json())->setResponse($response);
    }
}
