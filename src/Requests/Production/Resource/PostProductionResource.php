<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Resource\ResourcesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/resource`, body is a `ResourcesPostData`; the response is the saved resources,
 * under `Resources`.
 *
 * @extends WriteRequest<ResourcesData>
 */
final class PostProductionResource extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/resource';
    }

    public function createDtoFromResponse(Response $response): ResourcesData
    {
        return ResourcesData::from($response->json())->setResponse($response);
    }
}
