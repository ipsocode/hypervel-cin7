<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductFamily;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT productFamily`, body is a `ProductFamilyPutData` and carries the family's `ID`; the response is the list envelope holding the saved
 * family. A product's `SKU` and `Name` are ignored by Cin7, so they are left out of the body.
 *
 * @extends WriteRequest<ProductFamilyData>
 */
final class PutProductFamily extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = ProductFamilyData::class;

    protected ?string $dtoPath = 'ProductFamilies.0';

    /**
     * @var list<string>
     */
    protected array $omit = ['Products.*.SKU', 'Products.*.Name'];

    public function resolveEndpoint(): string
    {
        return 'productFamily';
    }
}
