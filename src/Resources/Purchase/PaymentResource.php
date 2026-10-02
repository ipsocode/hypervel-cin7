<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentPostData;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentPutData;
use Ipsocode\Cin7\Requests\Purchase\Payment\DeletePurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\GetPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\PostPurchasePayment;
use Ipsocode\Cin7\Requests\Purchase\Payment\PutPurchasePayment;

/**
 * `purchase/payment`, a purchase's payments. The reference marks it deprecated: it supports only
 * simple purchases, and an advanced purchase's payments are on `advanced-purchase/payment`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PaymentResource extends BaseResource
{
    /**
     * A purchase's payments, by its `TaskID`.
     */
    public function get(
        string $taskId,
    ): Response {
        return $this->connector->send(new GetPurchasePayment($taskId));
    }

    /**
     * @param array<string, mixed>|PurchasePaymentPostData $body
     */
    public function post(array|PurchasePaymentPostData $body): Response
    {
        return $this->connector->send(new PostPurchasePayment($body));
    }

    /**
     * @param array<string, mixed>|PurchasePaymentPutData $body
     */
    public function put(array|PurchasePaymentPutData $body): Response
    {
        return $this->connector->send(new PutPurchasePayment($body));
    }

    /**
     * Delete one payment and, unless `deleteAllocation` is false, its allocated payments.
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
