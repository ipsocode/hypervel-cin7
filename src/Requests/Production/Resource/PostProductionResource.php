<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ResourcesData::class;

    public function resolveEndpoint(): string
    {
        return 'production/resource';
    }
}
