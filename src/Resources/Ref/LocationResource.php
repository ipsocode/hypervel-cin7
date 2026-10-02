<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Location\LocationPostData;
use Ipsocode\Cin7\Data\Ref\Location\LocationPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Location\DeleteLocation;
use Ipsocode\Cin7\Requests\Ref\Location\GetLocation;
use Ipsocode\Cin7\Requests\Ref\Location\PostLocation;
use Ipsocode\Cin7\Requests\Ref\Location\PutLocation;

/**
 * `ref/location`, the locations.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class LocationResource extends BaseResource
{
    /**
     * One page of locations; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the location with this ID
     * @param null|bool $deprecated only deprecated locations
     * @param null|string $name only locations whose name starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?bool $deprecated = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetLocation(
            $page,
            $limit,
            $id,
            $deprecated,
            $name,
        ));
    }

    /**
     * Every page of locations, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the location with this ID
     * @param null|bool $deprecated only deprecated locations
     * @param null|string $name only locations whose name starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?bool $deprecated = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetLocation(
            null,
            $limit,
            $id,
            $deprecated,
            $name,
        ));
    }

    /**
     * @param array<string, mixed>|LocationPostData $body
     */
    public function post(array|LocationPostData $body): Response
    {
        return $this->connector->send(new PostLocation($body));
    }

    /**
     * @param array<string, mixed>|LocationPutData $body
     */
    public function put(array|LocationPutData $body): Response
    {
        return $this->connector->send(new PutLocation($body));
    }

    /**
     * Delete a location.
     *
     * @param string $id the ID of the location
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteLocation($id));
    }
}
