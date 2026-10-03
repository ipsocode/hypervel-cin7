<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Production\ResourceList\GetProductionResourceList;
use Ipsocode\Cin7\Resources\ListResource;

/**
 * `production/resourceList`, the resourceList resource.
 */
final class ResourceListResource extends ListResource
{
    /**
     * One page of Resources; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only those whose name or code starts with this
     * @param null|bool $onlyActive only active resources (the default Cin7 applies is true)
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $name = null,
        ?bool $onlyActive = null,
    ): Response {
        return $this->sendList(new GetProductionResourceList(
            $page,
            $limit,
            $name,
            $onlyActive,
        ));
    }

    /**
     * Every page of Resources, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only those whose name or code starts with this
     * @param null|bool $onlyActive only active resources (the default Cin7 applies is true)
     */
    public function paginate(
        ?int $limit = null,
        ?string $name = null,
        ?bool $onlyActive = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetProductionResourceList(
            null,
            $limit,
            $name,
            $onlyActive,
        ));
    }
}
