<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\PaymentTerm;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/paymentterm`, body is a `PaymentTermPostData`; the response is the list envelope
 * holding the saved payment term.
 *
 * @extends WriteRequest<PaymentTermData>
 */
final class PostPaymentTerm extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = PaymentTermData::class;

    protected ?string $dtoPath = 'PaymentTermList.0';

    public function resolveEndpoint(): string
    {
        return 'ref/paymentterm';
    }
}
