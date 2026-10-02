<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Payment;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `DELETE sale/payment?ID`, removes one payment (there is no `Void`); the response `{Success}` is left to `json()`.
 *
 * @extends KeyedRequest<null>
 */
final class DeleteSalePayment extends KeyedRequest
{
    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return 'sale/payment';
    }
}
