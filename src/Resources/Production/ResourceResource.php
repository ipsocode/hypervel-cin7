<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcePutData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcesData;
use Ipsocode\Cin7\Data\Production\Resource\ResourcesPostData;
use Ipsocode\Cin7\Requests\Production\Resource\DeleteProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\GetProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\PostProductionResource;
use Ipsocode\Cin7\Requests\Production\Resource\PutProductionResource;

/**
 * `production/resource`, the resource resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ResourceResource extends BaseResource
{
    /**
     * One resource.
     *
     * @param null|bool $includeAttachments include the attachments
     * @return Response<ResourceData>
     */
    public function get(string $resourceId, ?bool $includeAttachments = null): Response
    {
        return $this->connector->send(new GetProductionResource($resourceId, $includeAttachments));
    }

    /**
     * @param array<string, mixed>|ResourcesPostData $body
     * @return Response<ResourcesData>
     */
    public function post(array|ResourcesPostData $body): Response
    {
        return $this->connector->send(new PostProductionResource($body));
    }

    /**
     * @param array<string, mixed>|ResourcePutData $body
     * @return Response<ResourceData>
     */
    public function put(array|ResourcePutData $body): Response
    {
        return $this->connector->send(new PutProductionResource($body));
    }

    /**
     * Deletes a resource; the response is the resource.
     *
     * @return Response<ResourceData>
     */
    public function delete(string $resourceId): Response
    {
        return $this->connector->send(new DeleteProductionResource($resourceId));
    }
}
