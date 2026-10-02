<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST customer`, body is a Customer.
 *
 * @extends WriteRequest<mixed>
 */
final class PostCustomer extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'customer';
    }
}
