<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT customer`, body is a Customer and carries `ID`.
 *
 * @extends WriteRequest<mixed>
 */
final class PutCustomer extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'customer';
    }
}
