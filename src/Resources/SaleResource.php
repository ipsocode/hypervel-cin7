<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\SalePostData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Enums\CountryFormat;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Resources\Sale\AttachmentResource;
use Ipsocode\Cin7\Resources\Sale\CreditNoteResource;
use Ipsocode\Cin7\Resources\Sale\FulfilmentResource;
use Ipsocode\Cin7\Resources\Sale\InvoiceResource;
use Ipsocode\Cin7\Resources\Sale\ManualJournalResource;
use Ipsocode\Cin7\Resources\Sale\OrderResource;
use Ipsocode\Cin7\Resources\Sale\PaymentResource;
use Ipsocode\Cin7\Resources\Sale\QuoteResource;

/**
 * `sale` has no list action; list sales through `saleList()`. `order()`, `invoice()`,
 * `creditNote()` and `payment()` are the `sale/…` sub-resources.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class SaleResource extends BaseResource
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
     * @param array<string, mixed>|SalePostData $body
     */
    public function post(array|SalePostData $body): Response
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

    public function quote(): QuoteResource
    {
        return new QuoteResource($this->connector);
    }

    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }

    public function fulfilment(): FulfilmentResource
    {
        return new FulfilmentResource($this->connector);
    }

    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this->connector);
    }

    public function creditNote(): CreditNoteResource
    {
        return new CreditNoteResource($this->connector);
    }

    public function payment(): PaymentResource
    {
        return new PaymentResource($this->connector);
    }

    public function manualJournal(): ManualJournalResource
    {
        return new ManualJournalResource($this->connector);
    }

    public function attachment(): AttachmentResource
    {
        return new AttachmentResource($this->connector);
    }
}
