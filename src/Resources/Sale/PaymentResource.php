<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
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
     * @param array<string, mixed> $parameters
     */
    public function get(string $saleId, array $parameters = []): Response
    {
        return $this->connector->send(new GetSalePayment($saleId, $parameters));
    }

    /**
     * @param array<string, mixed>|SalePaymentLinePartialData $body
     */
    public function post(array|SalePaymentLinePartialData $body): Response
    {
        return $this->connector->send(new PostSalePayment($body));
    }

    /**
     * @param array<string, mixed>|SalePaymentLinePartialData $body
     */
    public function put(array|SalePaymentLinePartialData $body): Response
    {
        return $this->connector->send(new PutSalePayment($body));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteSalePayment($id));
    }
}
