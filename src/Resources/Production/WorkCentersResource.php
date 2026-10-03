<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCentersData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Production\WorkCenters\DeleteProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\GetProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\PostProductionWorkCenters;
use Ipsocode\Cin7\Requests\Production\WorkCenters\PutProductionWorkCenters;

/**
 * `production/workcenters`, the workCenters resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class WorkCentersResource extends BaseResource
{
    /**
     * One page of Workcenters; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only those whose name or code starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetProductionWorkCenters(
            $page,
            $limit,
            $name,
        ));
    }

    /**
     * Every page of Workcenters, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only those whose name or code starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetProductionWorkCenters(
            null,
            $limit,
            $name,
        ));
    }

    /**
     * @param array<string, mixed>|WorkCentersData $body
     */
    public function post(array|WorkCentersData $body): Response
    {
        return $this->connector->send(new PostProductionWorkCenters($body));
    }

    /**
     * @param array<string, mixed>|WorkCentersData $body
     */
    public function put(array|WorkCentersData $body): Response
    {
        return $this->connector->send(new PutProductionWorkCenters($body));
    }

    /**
     * Deletes a work center; the response is not documented.
     */
    public function delete(string $workCenterId): Response
    {
        return $this->connector->send(new DeleteProductionWorkCenters($workCenterId));
    }
}
