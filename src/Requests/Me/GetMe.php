<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Me\MeData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET me`, the company the API application belongs to and its settings; it takes no parameters.
 *
 * @extends Cin7Request<MeData>
 */
final class GetMe extends Cin7Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'me';
    }

    public function createDtoFromResponse(Response $response): MeData
    {
        return MeData::from($response->json())->setResponse($response);
    }
}
