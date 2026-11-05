<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Category;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ProductCategoryData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/category';
    }
}
