<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class ProductResource extends BaseResource
{
    /**
     * @param array<string, mixed> $filters
     */
    public function get(array $filters = []): Response
    {
        return $this->connector->send(new GetProduct($filters));
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters = []): Cin7Paginator
    {
        return $this->connector->paginate(new GetProduct($filters));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function post(array $body): Response
    {
        return $this->connector->send(new PostProduct($body));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function put(array $body): Response
    {
        return $this->connector->send(new PutProduct($body));
    }
}
