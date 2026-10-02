<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class SaleListResource extends BaseResource
{
    /**
     * @param array<string, mixed> $filters
     */
    public function get(array $filters = []): Response
    {
        return $this->connector->send(new GetSaleList($filters));
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters = []): Cin7Paginator
    {
        return $this->connector->paginate(new GetSaleList($filters));
    }
}
