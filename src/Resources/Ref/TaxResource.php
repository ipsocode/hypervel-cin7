<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class TaxResource extends BaseResource
{
    /**
     * @param array<string, mixed> $filters
     */
    public function get(array $filters = []): Response
    {
        return $this->connector->send(new GetTax($filters));
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters = []): Cin7Paginator
    {
        return $this->connector->paginate(new GetTax($filters));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function post(array $body): Response
    {
        return $this->connector->send(new PostTax($body));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function put(array $body): Response
    {
        return $this->connector->send(new PutTax($body));
    }
}
