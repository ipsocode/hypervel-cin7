<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use InvalidArgumentException;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Product\ProductPutData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT product`, body is a `ProductPutData`, and must carry a non-empty `ID`, which Cin7 requires on PUT;
 * the response is the saved Product. `Type` is read-only for PUT, and the fields read-only on
 * POST too (`AverageCost`, `LastModifiedOn`, `BOMType`, the supplier `Currency`, the BOM `Name`,
 * the custom-price `ProductName`) are left out of the body.
 *
 * @throws InvalidArgumentException when the body has no `ID`
 *
 * @extends WriteRequest<ProductData>
 */
final class PutProduct extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @var list<string>
     */
    protected array $omit = [
        'Type',
        'AverageCost',
        'LastModifiedOn',
        'BOMType',
        'Suppliers.*.Currency',
        'BillOfMaterialsProducts.*.Name',
        'CustomPrices.*.ProductName',
    ];

    /**
     * @param array<string, mixed>|ProductPutData $body
     */
    public function __construct(array|ProductPutData $body)
    {
        $id = $body instanceof ProductPutData ? $body->ID : ($body['ID'] ?? null);

        if (! is_string($id) || $id === '') {
            throw new InvalidArgumentException('PUT product needs the Product ID.');
        }

        parent::__construct($body);
    }

    public function resolveEndpoint(): string
    {
        return 'product';
    }

    public function createDtoFromResponse(Response $response): ProductData
    {
        return ProductData::from($response->json('Products.0'))->setResponse($response);
    }
}
