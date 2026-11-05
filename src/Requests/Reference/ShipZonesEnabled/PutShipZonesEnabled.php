<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZonesEnabled;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Reference\ShipZonesEnabled\ShipZonesEnabledData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT reference/shipZonesEnabled`, body is a `ShipZonesEnabledData`; the response is the setting.
 *
 * @extends WriteRequest<ShipZonesEnabledData>
 */
final class PutShipZonesEnabled extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = ShipZonesEnabledData::class;

    public function resolveEndpoint(): string
    {
        return 'reference/shipZonesEnabled';
    }
}
