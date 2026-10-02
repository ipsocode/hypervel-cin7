<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET product` — the list envelope is keyed `Products`, not `ProductList`.
 *
 * @extends ListRequest<mixed>
 */
final class GetProduct extends ListRequest
{
    protected string $listKey = 'Products';

    public function resolveEndpoint(): string
    {
        return 'product';
    }
}
