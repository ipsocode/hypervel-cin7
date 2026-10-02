<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZoneData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT reference/shipZones`, body is a `ShippingZonePutData`; the response is the saved zone, in `ShipZones`.
 *
 * @extends WriteRequest<ShippingZoneData>
 */
final class PutShipZones extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'reference/shipZones';
    }

    public function createDtoFromResponse(Response $response): ShippingZoneData
    {
        return ShippingZoneData::from($response->json('ShipZones.0'))->setResponse($response);
    }
}
