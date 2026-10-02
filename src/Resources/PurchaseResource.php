<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource;

/**
 * `purchase`, a simple purchase; `payment()` is the `purchase/payment` sub-resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PurchaseResource extends BaseResource
{
    /**
     * The `purchase/payment` resource, a purchase's payments.
     */
    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }
}
