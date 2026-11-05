<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Addresses;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST me/addresses`, body is a `MeAddressPostData`; the response is the list envelope holding
 * the saved address.
 *
 * @extends WriteRequest<MeAddressData>
 */
final class PostMeAddresses extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = MeAddressData::class;

    protected ?string $dtoPath = 'MeAddressesList.0';

    public function resolveEndpoint(): string
    {
        return 'me/addresses';
    }
}
