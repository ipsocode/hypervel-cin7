<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Location;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\Location\LocationData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/location`, body is a `LocationPostData`; the response is the saved location, not a list.
 *
 * @extends WriteRequest<LocationData>
 */
final class PostLocation extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = LocationData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/location';
    }
}
