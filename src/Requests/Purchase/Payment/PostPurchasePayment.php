<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/payment`, body is a `PurchasePaymentPostData` or an array; the response is the
 * saved payment. `ID` is available only for PUT, and `DateCreated` is the date Cin7 stamps on the
 * record, so both are left out of the body.
 *
 * @extends WriteRequest<PurchasePaymentData>
 */
final class PostPurchasePayment extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['ID', 'DateCreated'];

    public function resolveEndpoint(): string
    {
        return 'purchase/payment';
    }

    public function createDtoFromResponse(Response $response): PurchasePaymentData
    {
        return PurchasePaymentData::from($response->json())->setResponse($response);
    }
}
