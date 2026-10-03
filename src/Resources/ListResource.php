<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * Base for the resource of a list-only endpoint such as `saleList`: the subclass declares `get()`
 * and `paginate()` with the endpoint's typed filters and hands the request to the two methods here.
 *
 * @see docs/resources.md
 *
 * @extends BaseResource<Cin7Connector>
 */
abstract class ListResource extends BaseResource
{
    /**
     * @param ListRequest<mixed> $request
     */
    protected function sendList(ListRequest $request): Response
    {
        return $this->connector->send($request);
    }

    /**
     * @param ListRequest<mixed> $request
     */
    protected function paginateList(ListRequest $request): Cin7Paginator
    {
        return $this->connector->paginate($request);
    }
}
