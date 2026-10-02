<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchasePartialInvoicePostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\DeleteAdvancedPurchaseInvoice;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\GetAdvancedPurchaseInvoice;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice\PostAdvancedPurchaseInvoice;

/**
 * `advanced-purchase/invoice`, an advanced purchase's invoices. A simple purchase's invoice is on
 * `purchase/invoice`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class InvoiceResource extends BaseResource
{
    /**
     * An advanced purchase's invoices, by its `PurchaseID`.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $purchaseId,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchaseInvoice($purchaseId, $combineAdditionalCharges));
    }

    /**
     * @param AdvancedPurchasePartialInvoicePostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePartialInvoicePostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchaseInvoice($body));
    }

    /**
     * Void the invoice task (`void: true`), or undo it (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo (false)
     */
    public function delete(
        string $taskId,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteAdvancedPurchaseInvoice($taskId, $void));
    }
}
