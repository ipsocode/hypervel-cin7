<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Purchase\AttachmentResource;
use Ipsocode\Cin7\Resources\Purchase\CreditNoteResource;
use Ipsocode\Cin7\Resources\Purchase\InvoiceResource;
use Ipsocode\Cin7\Resources\Purchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\Purchase\OrderResource;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource;
use Ipsocode\Cin7\Resources\Purchase\StockResource;

/**
 * `purchase`, a simple purchase; `order()`, `stock()`, `invoice()`, `creditNote()`, `payment()`,
 * `manualJournal()` and `attachment()` are the `purchase/order`, `purchase/stock`,
 * `purchase/invoice`, `purchase/creditnote`, `purchase/payment`, `purchase/manualJournal` and
 * `purchase/attachment` sub-resources.
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
     * The `purchase/invoice` resource, a purchase's invoice.
     */
    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this->connector);
    }

    /**
     * The `purchase/creditnote` resource, a purchase's credit note.
     */
    public function creditNote(): CreditNoteResource
    {
        return new CreditNoteResource($this->connector);
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

    /**
     * The `purchase/attachment` resource, a purchase's attachments.
     */
    public function attachment(): AttachmentResource
    {
        return new AttachmentResource($this->connector);
    }
}
