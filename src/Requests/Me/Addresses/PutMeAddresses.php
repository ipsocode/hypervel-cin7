<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Addresses;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = MeAddressData::class;

    protected ?string $dtoPath = 'MeAddressesList.0';

    public function resolveEndpoint(): string
    {
        return 'me/addresses';
    }
}
