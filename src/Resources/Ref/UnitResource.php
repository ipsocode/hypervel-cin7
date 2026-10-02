<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasurePostData;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasurePutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Unit\DeleteUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\GetUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\PostUnit;
use Ipsocode\Cin7\Requests\Ref\Unit\PutUnit;

/**
 * `ref/unit`, the units of measure.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class UnitResource extends BaseResource
{
    /**
     * One page of units of measure; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only units of measure whose name starts with this
     */
    public function get(?int $page = null, ?int $limit = null, ?string $name = null): Response
    {
        return $this->connector->send(new GetUnit($page, $limit, $name));
    }

    /**
     * Every page of units of measure, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only units of measure whose name starts with this
     */
    public function paginate(?int $limit = null, ?string $name = null): Cin7Paginator
    {
        return $this->connector->paginate(new GetUnit(null, $limit, $name));
    }

    /**
     * @param array<string, mixed>|UnitOfMeasurePostData $body
     */
    public function post(array|UnitOfMeasurePostData $body): Response
    {
        return $this->connector->send(new PostUnit($body));
    }

    /**
     * @param array<string, mixed>|UnitOfMeasurePutData $body
     */
    public function put(array|UnitOfMeasurePutData $body): Response
    {
        return $this->connector->send(new PutUnit($body));
    }

    /**
     * Delete the unit of measure with this ID.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteUnit($id));
    }
}
