<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Ref\Customer\CreditsResource;
use Ipsocode\Cin7\Resources\Ref\Customer\TemplatesResource;

/**
 * Groups the `ref/customer/…` resources; V2 has no action on `/ref/customer` itself.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CustomerResource extends BaseResource
{
    /**
     * The `ref/customer/credits` resource.
     */
    public function credits(): CreditsResource
    {
        return new CreditsResource($this->connector);
    }

    /**
     * The `ref/customer/templates` resource, the customers' default templates.
     */
    public function templates(): TemplatesResource
    {
        return new TemplatesResource($this->connector);
    }
}
