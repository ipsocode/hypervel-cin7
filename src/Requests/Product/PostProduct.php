<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST product`, body is a `ProductPostData`; the response is the saved Product. Cin7 ignores
 * `ID` on POST and marks `AverageCost`, `LastModifiedOn`, `BOMType` and the supplier `Currency`,
 * the BOM `Name` and the custom-price `ProductName` read-only, so they are left out of the body.
 *
 * @extends WriteRequest<ProductData>
 */
final class PostProduct extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = [
        'ID',
        'AverageCost',
        'LastModifiedOn',
        'BOMType',
        'Suppliers.*.Currency',
        'BillOfMaterialsProducts.*.Name',
        'CustomPrices.*.ProductName',
    ];

    public function resolveEndpoint(): string
    {
        return 'product';
    }

    public function createDtoFromResponse(Response $response): ProductData
    {
        return ProductData::from($response->json('Products.0'))->setResponse($response);
    }
}
