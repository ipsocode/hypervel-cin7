<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Purchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\Purchase\OrderResource;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource;
use Ipsocode\Cin7\Resources\Purchase\StockResource;

/**
 * `purchase`, a simple purchase; `order()`, `stock()`, `payment()` and `manualJournal()` are the
 * `purchase/order`, `purchase/stock`, `purchase/payment` and `purchase/manualJournal`
 * sub-resources.
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
     * The `purchase/stock` resource, a purchase's stock received.
     */
    public function stock(): StockResource
    {
        return new StockResource($this->connector);
    }

    /**
     * The `purchase/payment` resource, a purchase's payments.
     */
    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }

    /**
     * The `purchase/manualJournal` resource, a purchase's manual journal.
     */
    public function manualJournal(): ManualJournalResource
    {
        return new ManualJournalResource($this->connector);
    }
}
