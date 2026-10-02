<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPutData;
use Ipsocode\Cin7\Requests\Sale\Payment\DeleteSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\GetSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\PutSalePayment;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class PaymentResource extends BaseResource
{
    /**
     * A sale's payments.
     */
    public function get(
        string $saleId,
    ): Response {
        return $this->connector->send(new GetSalePayment($saleId));
    }

    /**
     * @param array<string, mixed>|SalePaymentPostData $body
     */
    public function post(array|SalePaymentPostData $body): Response
    {
        return $this->connector->send(new PostSalePayment($body));
    }

    /**
     * @param array<string, mixed>|SalePaymentPutData $body
     */
    public function put(array|SalePaymentPutData $body): Response
    {
        return $this->connector->send(new PutSalePayment($body));
    }

    /**
     * Delete one payment; there is no void.
     */
    public function delete(
        string $id,
    ): Response {
        return $this->connector->send(new DeleteSalePayment($id));
    }
}
