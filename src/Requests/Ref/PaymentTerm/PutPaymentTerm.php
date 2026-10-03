<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\PaymentTerm;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/paymentterm`, body is a `PaymentTermPutData` and carries the payment term's `ID`; the
 * response is the list envelope holding the saved payment term.
 *
 * @extends WriteRequest<PaymentTermData>
 */
final class PutPaymentTerm extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/paymentterm';
    }

    public function createDtoFromResponse(Response $response): PaymentTermData
    {
        return PaymentTermData::from($response->json('PaymentTermList.0'))->setResponse($response);
    }
}
