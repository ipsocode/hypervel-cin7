<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePutData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\DeleteAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\GetAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PostAdvancedPurchase;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAdvancedPurchase;
use Ipsocode\Cin7\Resources\AdvancedPurchase\CreditNoteResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\InvoiceResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\ManualJournalResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PaymentResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\PutAwayResource;
use Ipsocode\Cin7\Resources\AdvancedPurchase\StockResource;

/**
 * `advanced-purchase`, a purchase of any kind, simple, advanced or service: `get()`, `post()`,
 * `put()` and `delete()` read, create, change and void one, each answering with the purchase and
 * every document it holds. `advanced-purchase` has no list action; list purchases through
 * `purchaseList()`.
 *
 * `stock()`, `putAway()`, `invoice()`, `creditNote()`, `payment()` and `manualJournal()` are the
 * `advanced-purchase/stock`, `advanced-purchase/put-away`, `advanced-purchase/invoice`,
 * `advanced-purchase/creditnote`, `advanced-purchase/payment` and `advanced-purchase/manualJournal`
 * sub-resources, each read by the purchase's `ID`, sent as `PurchaseID`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AdvancedPurchaseResource extends BaseResource
{
    /**
     * One purchase, with its order and its stock received, put away, invoices, credit notes and
     * manual journals.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $id,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchase($id, $combineAdditionalCharges));
    }

    /**
     * @param AdvancedPurchasePostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchase($body));
    }

    /**
     * @param AdvancedPurchasePutData|array<string, mixed> $body
     */
    public function put(array|AdvancedPurchasePutData $body): Response
    {
        return $this->connector->send(new PutAdvancedPurchase($body));
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
        return $this->connector->send(new DeleteAdvancedPurchase($id, $void));
    }

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
