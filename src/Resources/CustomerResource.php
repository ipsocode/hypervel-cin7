<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class CustomerResource extends BaseResource
{
    /**
     * @param array<string, mixed> $filters
     */
    public function get(array $filters = []): Response
    {
        return $this->connector->send(new GetCustomer($filters));
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters = []): Cin7Paginator
    {
        return $this->connector->paginate(new GetCustomer($filters));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function post(array $body): Response
    {
        return $this->connector->send(new PostCustomer($body));
    }

    /**
     * @param array<string, mixed> $body
     */
    public function put(array $body): Response
    {
        return $this->connector->send(new PutCustomer($body));
    }
}
