<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
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
     * @param array<string, mixed> $parameters
     */
    public function get(string $saleId, array $parameters = []): Response
    {
        return $this->connector->send(new GetSaleInvoice($saleId, $parameters));
    }

    /**
     * @param array<string, mixed>|SaleInvoicePostData $body
     */
    public function post(array|SaleInvoicePostData $body): Response
    {
        return $this->connector->send(new PostSaleInvoice($body));
    }

    /**
     * @param array<string, mixed>|SaleInvoicePostData $body
     */
    public function put(array|SaleInvoicePostData $body): Response
    {
        return $this->connector->send(new PutSaleInvoice($body));
    }

    /**
     * Void the invoice (`$void = true`), or undo a void.
     */
    public function delete(string $taskId, bool $void = false): Response
    {
        return $this->connector->send(new DeleteSaleInvoice($taskId, ['Void' => $void]));
    }
}
