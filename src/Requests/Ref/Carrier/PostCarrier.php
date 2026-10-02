<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Carrier;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/carrier`, body is a `CarrierPostData`; the response is a `CarrierList` holding the saved carrier.
 *
 * @extends WriteRequest<list<CarrierData>>
 */
final class PostCarrier extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'ref/carrier';
    }

    /**
     * @return list<CarrierData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): CarrierData => CarrierData::from($item)->setResponse($response),
            array_values($response->json('CarrierList')),
        );
    }
}
