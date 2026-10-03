<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentPutData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\GetAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\PostAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Payment\PutAdvancedPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\DeletePurchasePayment;

/**
 * `advanced-purchase/payment`, an advanced purchase's payments. A payment needs an authorised
 * invoice, and a refund an authorised credit note. The reference documents its DELETE on
 * `purchase/payment`, so `delete()` sends `DeletePurchasePayment`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PaymentResource extends BaseResource
{
    /**
     * An advanced purchase's payments, by its `PurchaseID` or by the number of its order, invoice
     * or credit note.
     *
     * @param null|string $purchaseId the advanced purchase's ID
     * @param null|string $orderNumber the purchase order's number
     * @param null|string $invoiceNumber the invoice's number
     * @param null|string $creditNoteNumber the credit note's number
     */
    public function get(
        ?string $purchaseId = null,
        ?string $orderNumber = null,
        ?string $invoiceNumber = null,
        ?string $creditNoteNumber = null,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchasePayment($purchaseId, $orderNumber, $invoiceNumber, $creditNoteNumber));
    }

    /**
     * @param AdvancedPurchasePaymentPostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePaymentPostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchasePayment($body));
    }

    /**
     * @param AdvancedPurchasePaymentPutData|array<string, mixed> $body
     */
    public function put(array|AdvancedPurchasePaymentPutData $body): Response
    {
        return $this->connector->send(new PutAdvancedPurchasePayment($body));
    }

    /**
     * Delete one payment and, unless `deleteAllocation` is false, its allocated payments, through
     * `purchase/payment`, where the reference documents this DELETE.
     *
     * @param null|bool $deleteAllocation delete the allocated payments too (true, the default Cin7
     *                                    applies) or keep them (false)
     */
    public function delete(
        string $id,
        ?bool $deleteAllocation = null,
    ): Response {
        return $this->connector->send(new DeletePurchasePayment($id, $deleteAllocation));
    }
}
