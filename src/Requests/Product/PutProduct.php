<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT product`, body is a Product, and carries `ID`; the response is the saved Product.
 *
 * @extends WriteRequest<ProductData>
 */
final class PutProduct extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'product';
    }

    public function createDtoFromResponse(Response $response): ProductData
    {
        return ProductData::from($response->json('Products.0'))->setResponse($response);
    }
}
