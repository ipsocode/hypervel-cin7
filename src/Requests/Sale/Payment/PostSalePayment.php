<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/payment`, body is a Sale Payment Line; the response is the saved line.
 * `ID` and `CreditID` are available only for PUT, so both are left out of the body.
 *
 * @extends WriteRequest<SalePaymentLinePartialData>
 */
final class PostSalePayment extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['ID', 'CreditID'];

    public function resolveEndpoint(): string
    {
        return 'sale/payment';
    }

    public function createDtoFromResponse(Response $response): SalePaymentLinePartialData
    {
        return SalePaymentLinePartialData::from($response->json())->setResponse($response);
    }
}
