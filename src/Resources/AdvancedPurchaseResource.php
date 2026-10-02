<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\AdvancedPurchase\CreditNoteResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\InvoiceResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PaymentResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PutAwayResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\StockResource;

/**
 * `advanced-purchase`, an advanced purchase; `stock()`, `putAway()`, `invoice()`, `creditNote()`,
 * `payment()` and `manualJournal()` are the `advanced-purchase/stock`,
 * `advanced-purchase/put-away`, `advanced-purchase/invoice`, `advanced-purchase/creditnote`,
 * `advanced-purchase/payment` and `advanced-purchase/manualJournal` sub-resources.
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
     * The `advanced-purchase/put-away` resource, an advanced purchase's put away.
     */
    public function putAway(): PutAwayResource
    {
        return new PutAwayResource($this->connector);
    }

    /**
     * The `advanced-purchase/invoice` resource, an advanced purchase's invoices.
     */
    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this->connector);
    }

    /**
     * The `advanced-purchase/creditnote` resource, an advanced purchase's credit notes.
     */
    public function creditNote(): CreditNoteResource
    {
        return new CreditNoteResource($this->connector);
    }

    /**
     * The `advanced-purchase/payment` resource, an advanced purchase's payments.
     */
    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }

    /**
     * The `advanced-purchase/manualJournal` resource, an advanced purchase's manual journals.
     */
    public function manualJournal(): ManualJournalResource
    {
        return new ManualJournalResource($this->connector);
    }
}
