<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedSale\AdvancedSalePostData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Enums\CountryFormat;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Resources\Sale\CreditNoteResource;
use Ipsocode\Cin7\Resources\Sale\FulfilmentResource;
use Ipsocode\Cin7\Resources\Sale\InvoiceResource;
use Ipsocode\Cin7\Resources\Sale\ManualJournalResource;
use Ipsocode\Cin7\Resources\Sale\PaymentResource;

/**
 * An advanced sale, one with several fulfilments, invoices and credit notes. The reference has no
 * `advanced-sale` endpoint: it serves the advanced sale through `sale`, so every method here sends
 * the `sale` request, and `advancedSale()` is `sale()` read the way `advancedPurchase()` is.
 *
 * `post()` takes an `AdvancedSalePostData`, which sends `SaleType: Advanced`. `get()`, `put()` and
 * `delete()` are `sale`'s and answer with the same `SaleData`, whose `Type` says `Advanced Sale`.
 * `fulfilment()`, `invoice()`, `creditNote()`, `payment()` and `manualJournal()` are the `sale/…`
 * resources: a second fulfilment turns a simple sale into an advanced one, and the DELETE of a
 * fulfilment, an invoice or a credit note works on advanced sales only.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AdvancedSaleResource extends BaseResource
{
    /**
     * One sale, with every model it nests.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     * @param null|bool $hideInventoryMovements leave out the inventory movements
     * @param null|bool $includeTransactions include the related transactions
     * @param null|CountryFormat $countryFormat return each address's country as its name or as a
     *                                          country code
     */
    public function get(
        string $id,
        ?bool $combineAdditionalCharges = null,
        ?bool $hideInventoryMovements = null,
        ?bool $includeTransactions = null,
        ?CountryFormat $countryFormat = null,
    ): Response {
        return $this->connector->send(new GetSale(
            $id,
            $combineAdditionalCharges,
            $hideInventoryMovements,
            $includeTransactions,
            $countryFormat,
        ));
    }

    /**
     * Create an advanced sale. An array body is sent as it is, so it names `SaleType: Advanced`
     * itself.
     *
     * @param AdvancedSalePostData|array<string, mixed> $body
     */
    public function post(array|AdvancedSalePostData $body): Response
    {
        return $this->connector->send(new PostSale($body));
    }

    /**
     * @param array<string, mixed>|SalePutData $body
     */
    public function put(array|SalePutData $body): Response
    {
        return $this->connector->send(new PutSale($body));
    }

    /**
     * Void the sale (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(
        string $id,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteSale($id, $void));
    }

    /**
     * The `sale/fulfilment` resource: its DELETE works on advanced sales only.
     */
    public function fulfilment(): FulfilmentResource
    {
        return new FulfilmentResource($this->connector);
    }

    /**
     * The `sale/invoice` resource: its DELETE works on advanced sales only.
     */
    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this->connector);
    }

    /**
     * The `sale/creditnote` resource: its DELETE works on advanced sales only.
     */
    public function creditNote(): CreditNoteResource
    {
        return new CreditNoteResource($this->connector);
    }

    /**
     * The `sale/payment` resource.
     */
    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }

    /**
     * The `sale/manualJournal` resource.
     */
    public function manualJournal(): ManualJournalResource
    {
        return new ManualJournalResource($this->connector);
    }
}
