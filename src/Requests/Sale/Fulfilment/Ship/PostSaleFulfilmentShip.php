<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/fulfilment/ship`, body is a `SaleFulfilmentShipPostData`; the response is the saved
 * shipment. A line's `TrackingURL` is read-only, so it is left out of an array body.
 *
 * @extends WriteRequest<SaleFulfilmentShipData>
 */
final class PostSaleFulfilmentShip extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.TrackingURL'];

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/ship';
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentShipData
    {
        return SaleFulfilmentShipData::from($response->json())->setResponse($response);
    }
}
