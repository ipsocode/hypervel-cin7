<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductSuppliers;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST product-suppliers`, body is a `ProductSuppliersData`; the response `{Success}` is left to `json()`.
 *
 * @extends WriteRequest<null>
 */
final class PostProductSuppliers extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'product-suppliers';
    }
}
