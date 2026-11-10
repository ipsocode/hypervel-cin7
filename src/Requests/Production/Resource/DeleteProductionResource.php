<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE production/resource?ResourceID`, deletes a resource; the response is the resource.
 *
 * @extends Cin7Request<ResourceData>
 */
final class DeleteProductionResource extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = ResourceData::class;

    public function __construct(
        protected readonly string $resourceId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'production/resource';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ResourceID' => $this->resourceId,
        ]);
    }
}
