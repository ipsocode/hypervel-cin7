<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZoneData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST reference/shipZones`, body is a `ShippingZonePostData`; the response is the saved zone, in `ShipZones`.
 *
 * @extends WriteRequest<ShippingZoneData>
 */
final class PostShipZones extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'reference/shipZones';
    }

    public function createDtoFromResponse(Response $response): ShippingZoneData
    {
        return ShippingZoneData::from($response->json('ShipZones.0'))->setResponse($response);
    }
}
