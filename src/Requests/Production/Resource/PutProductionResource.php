<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/resource`, body is a `ResourcePutData`; the response is the saved resource.
 *
 * @extends WriteRequest<ResourceData>
 */
final class PutProductionResource extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/resource';
    }

    public function createDtoFromResponse(Response $response): ResourceData
    {
        return ResourceData::from($response->json())->setResponse($response);
    }
}
