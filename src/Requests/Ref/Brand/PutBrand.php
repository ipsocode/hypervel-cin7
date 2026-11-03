<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Brand;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\Brand\BrandData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/brand`, body is a `BrandPutData` and carries the brand's `ID`; the response is the
 * saved brand, not a list.
 *
 * @extends WriteRequest<BrandData>
 */
final class PutBrand extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = BrandData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/brand';
    }
}
