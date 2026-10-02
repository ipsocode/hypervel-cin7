<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductFamily;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    /**
     * @var list<string>
     */
    protected array $omit = ['Products.*.SKU', 'Products.*.Name'];

    public function resolveEndpoint(): string
    {
        return 'productFamily';
    }

    public function createDtoFromResponse(Response $response): ProductFamilyData
    {
        return ProductFamilyData::from($response->json('ProductFamilies.0'))->setResponse($response);
    }
}
