<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\AdvancedPurchase\InvoiceResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PutAwayResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\StockResource;

/**
 * `advanced-purchase`, an advanced purchase; `stock()`, `putAway()`, `invoice()` and
 * `manualJournal()` are the `advanced-purchase/stock`, `advanced-purchase/put-away`,
 * `advanced-purchase/invoice` and `advanced-purchase/manualJournal` sub-resources.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AdvancedPurchaseResource extends BaseResource
{
    /**
     * The `advanced-purchase/stock` resource, an advanced purchase's stock received.
     */
    public function stock(): StockResource
    {
        return new StockResource($this->connector);
    }

    /**
     * The `advanced-purchase/manualJournal` resource, an advanced purchase's manual journals.
     */
    public function manualJournal(): ManualJournalResource
    {
        return new ManualJournalResource($this->connector);
    }

    /**
     * The `advanced-purchase/invoice` resource, an advanced purchase's invoices.
     */
    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this->connector);
    }

    /**
     * The `advanced-purchase/put-away` resource, an advanced purchase's put away.
     */
    public function putAway(): PutAwayResource
    {
        return new PutAwayResource($this->connector);
    }
}
