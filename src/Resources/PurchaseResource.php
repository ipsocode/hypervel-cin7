<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\PurchasePostData;
use Ipsocode\Cin7\Data\Purchase\PurchasePutData;
use Ipsocode\Cin7\Requests\Purchase\DeletePurchase;
use Ipsocode\Cin7\Requests\Purchase\GetPurchase;
use Ipsocode\Cin7\Requests\Purchase\PostPurchase;
use Ipsocode\Cin7\Requests\Purchase\PutPurchase;
use Ipsocode\Cin7\Resources\Purchase\AttachmentResource;
use Ipsocode\Cin7\Resources\Purchase\CreditNoteResource;
use Ipsocode\Cin7\Resources\Purchase\InvoiceResource;
use Ipsocode\Cin7\Resources\Purchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\Purchase\OrderResource;
use Ipsocode\Cin7\Resources\Purchase\PaymentResource;
use Ipsocode\Cin7\Resources\Purchase\StockResource;

/**
 * `purchase`, a simple purchase: `get()`, `post()`, `put()` and `delete()` read, create, change and
 * void one, each answering with the purchase and every document it holds. `purchase` has no list
 * action; list purchases through `purchaseList()`. The reference marks it deprecated: it supports
 * only simple purchases, and an advanced purchase is on `advanced-purchase`.
 *
 * `order()`, `stock()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()` and
 * `attachment()` are the `purchase/order`, `purchase/stock`, `purchase/invoice`,
 * `purchase/creditnote`, `purchase/payment`, `purchase/manualJournal` and `purchase/attachment`
 * sub-resources, each read by the purchase's `TaskID`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PurchaseResource extends BaseResource
{
    /**
     * One purchase, with its order, stock received, invoice, credit note and manual journal.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $id,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetPurchase($id, $combineAdditionalCharges));
    }

    /**
     * @param array<string, mixed>|PurchasePostData $body
     */
    public function post(array|PurchasePostData $body): Response
    {
        return $this->connector->send(new PostPurchase($body));
    }

    /**
     * @param array<string, mixed>|PurchasePutData $body
     */
    public function put(array|PurchasePutData $body): Response
    {
        return $this->connector->send(new PutPurchase($body));
    }

    /**
     * Void the purchase (`void: true`), or undo it (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo (false)
     */
    public function delete(
        string $id,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeletePurchase($id, $void));
    }

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
