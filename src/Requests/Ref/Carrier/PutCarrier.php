<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Carrier;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/carrier`, body is a `CarrierPutData` and carries the carrier's `CarrierID`; the response is a `CarrierList` holding the saved carrier.
 *
 * @extends WriteRequest<list<CarrierData>>
 */
final class PutCarrier extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/carrier';
    }

    /**
     * @return list<CarrierData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(CarrierData::class, $response, $response->json('CarrierList'));
    }
}
