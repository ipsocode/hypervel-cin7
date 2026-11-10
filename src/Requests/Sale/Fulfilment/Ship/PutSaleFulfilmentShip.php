<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale/fulfilment/ship`, body is a `SaleFulfilmentShipPutData`; the response is the saved
 * shipment. A line's `TrackingURL` is read-only, so it is left out of an array body.
 *
 * @extends WriteRequest<SaleFulfilmentShipData>
 */
final class PutSaleFulfilmentShip extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = SaleFulfilmentShipData::class;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.TrackingURL'];

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/ship';
    }
}
