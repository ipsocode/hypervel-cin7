<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Resource;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/resource?ResourceID`, one resource.
 *
 * @extends Cin7Request<ResourceData>
 */
final class GetProductionResource extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $resourceId,
        protected readonly ?bool $includeAttachments = null,
    ) {
        parent::__construct();
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
            'IncludeAttachments' => $this->includeAttachments,
        ]);
    }

    public function createDtoFromResponse(Response $response): ResourceData
    {
        return ResourceData::from($response->json())->setResponse($response);
    }
}
