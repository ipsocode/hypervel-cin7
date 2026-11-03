<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ResourceData::class;

    public function resolveEndpoint(): string
    {
        return 'production/resource';
    }
}
