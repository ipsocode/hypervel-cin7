<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref\Customer;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Customer\Credits\GetCustomerCredits;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class CreditsResource extends BaseResource
{
    /**
     * @param array<string, mixed> $filters
     */
    public function get(array $filters = []): Response
    {
        return $this->connector->send(new GetCustomerCredits($filters));
    }

    /**
     * The envelope has no `Total`, so `pool()` stops at page one; walk with `items()`.
     *
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters = []): Cin7Paginator
    {
        return $this->connector->paginate(new GetCustomerCredits($filters));
    }
}
