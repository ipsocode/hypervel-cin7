<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Brand;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\Brand\BrandData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/brand`, body is a `BrandPostData`; the response is the saved brand, not a list.
 *
 * @extends WriteRequest<BrandData>
 */
final class PostBrand extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = BrandData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/brand';
    }
}
