<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET customer` — the customer list envelope is keyed `CustomerList`.
 *
 * @extends ListRequest<mixed>
 */
final class GetCustomer extends ListRequest
{
    protected string $listKey = 'CustomerList';

    public function resolveEndpoint(): string
    {
        return 'customer';
    }
}
