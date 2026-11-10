<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ShippingZoneData::class;

    protected ?string $dtoPath = 'ShipZones.0';

    public function resolveEndpoint(): string
    {
        return 'reference/shipZones';
    }
}
