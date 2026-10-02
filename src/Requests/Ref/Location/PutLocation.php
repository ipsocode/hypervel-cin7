<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Location;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Location\LocationData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/location`, body is a `LocationPutData` and carries the location's `ID`; the response is the saved location, not a list.
 *
 * @extends WriteRequest<LocationData>
 */
final class PutLocation extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/location';
    }

    public function createDtoFromResponse(Response $response): LocationData
    {
        return LocationData::from($response->json())->setResponse($response);
    }
}
