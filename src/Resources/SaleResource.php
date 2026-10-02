<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\SalePostData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Resources\Sale\CreditNoteResource;
use Ipsocode\Cin7\Resources\Sale\InvoiceResource;
use Ipsocode\Cin7\Resources\Sale\OrderResource;
use Ipsocode\Cin7\Resources\Sale\PaymentResource;

/**
 * `sale` has no list action; list sales through `saleList()`. `order()`, `invoice()`,
 * `creditNote()` and `payment()` are the `sale/…` sub-resources.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class SaleResource extends BaseResource
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function get(string $id, array $parameters = []): Response
    {
        return $this->connector->send(new GetSale($id, $parameters));
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
     * Void the sale (`$void = true`), or undo a void.
     */
    public function delete(string $id, bool $void = false): Response
    {
        return $this->connector->send(new DeleteSale($id, ['Void' => $void]));
    }

    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
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
}
