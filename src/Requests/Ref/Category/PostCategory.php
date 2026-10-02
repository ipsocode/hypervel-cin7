<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Category;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/category`, body is a `ProductCategoryPostData`; the response is the saved product
 * category, not a list.
 *
 * @extends WriteRequest<ProductCategoryData>
 */
final class PostCategory extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'ref/category';
    }

    public function createDtoFromResponse(Response $response): ProductCategoryData
    {
        return ProductCategoryData::from($response->json())->setResponse($response);
    }
}
