<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale/payment`, body carries `ID`; the response is the saved line.
 *
 * @extends WriteRequest<SalePaymentLinePartialData>
 */
final class PutSalePayment extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'sale/payment';
    }

    public function createDtoFromResponse(Response $response): SalePaymentLinePartialData
    {
        return SalePaymentLinePartialData::from($response->json())->setResponse($response);
    }
}
