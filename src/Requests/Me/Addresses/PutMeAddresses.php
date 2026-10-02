<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Addresses;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT me/addresses`, body is a `MeAddressPutData` and carries the address's `AddressID`; the
 * response is the list envelope holding the saved address.
 *
 * @extends WriteRequest<MeAddressData>
 */
final class PutMeAddresses extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'me/addresses';
    }

    public function createDtoFromResponse(Response $response): MeAddressData
    {
        return MeAddressData::from($response->json('MeAddressesList.0'))->setResponse($response);
    }
}
