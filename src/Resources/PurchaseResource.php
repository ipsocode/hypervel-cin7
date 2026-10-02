<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Purchase\OrderResource;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource;

/**
 * `purchase`, a simple purchase; `order()` and `payment()` are the `purchase/order` and
 * `purchase/payment` sub-resources.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PurchaseResource extends BaseResource
{
    /**
     * The `purchase/order` resource, a purchase's order.
     */
    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }

    /**
     * The `purchase/payment` resource, a purchase's payments.
     */
    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }
}
