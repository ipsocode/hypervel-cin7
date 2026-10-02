<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductSuppliers;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT product-suppliers`, body is a `ProductSuppliersData`; the response `{Success}` is left to `json()`.
 *
 * @extends WriteRequest<null>
 */
final class PutProductSuppliers extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'product-suppliers';
    }
}
