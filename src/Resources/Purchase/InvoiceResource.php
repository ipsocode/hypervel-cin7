<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoicePostData;
use Ipsocode\Cin7\Requests\Purchase\Invoice\GetPurchaseInvoice;
use Ipsocode\Cin7\Requests\Purchase\Invoice\PostPurchaseInvoice;

/**
 * `purchase/invoice`, a purchase's invoice. The reference marks it deprecated: it supports only
 * simple purchases, and an advanced purchase's invoices are on `advanced-purchase/invoice`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class InvoiceResource extends BaseResource
{
    /**
     * A purchase's invoice, by the purchase's `TaskID`.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $taskId,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetPurchaseInvoice($taskId, $combineAdditionalCharges));
    }

    /**
     * @param array<string, mixed>|PurchaseInvoicePostData $body
     */
    public function post(array|PurchaseInvoicePostData $body): Response
    {
        return $this->connector->send(new PostPurchaseInvoice($body));
    }
}
