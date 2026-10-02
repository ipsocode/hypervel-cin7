<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePutData;
use Ipsocode\Cin7\Requests\Sale\Invoice\DeleteSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\GetSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Invoice\PutSaleInvoice;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class InvoiceResource extends BaseResource
{
    /**
     * A sale's invoices.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     * @param null|bool $includeProductInfo add the products the lines use
     */
    public function get(
        string $saleId,
        ?bool $combineAdditionalCharges = null,
        ?bool $includeProductInfo = null,
    ): Response {
        return $this->connector->send(new GetSaleInvoice(
            $saleId,
            $combineAdditionalCharges,
            $includeProductInfo,
        ));
    }

    /**
     * @param array<string, mixed>|SaleInvoicePostData $body
     */
    public function post(array|SaleInvoicePostData $body): Response
    {
        return $this->connector->send(new PostSaleInvoice($body));
    }

    /**
     * @param array<string, mixed>|SaleInvoicePutData $body
     */
    public function put(array|SaleInvoicePutData $body): Response
    {
        return $this->connector->send(new PutSaleInvoice($body));
    }

    /**
     * Void the invoice (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(
        string $taskId,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteSaleInvoice($taskId, $void));
    }
}
