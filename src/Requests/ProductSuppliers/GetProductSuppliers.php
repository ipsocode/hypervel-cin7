<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductSuppliers;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\ProductSuppliers\ProductSuppliersData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET product-suppliers?ProductID`, a product's suppliers, in `ProductSuppliers`.
 *
 * @extends Cin7Request<ProductSuppliersData>
 */
final class GetProductSuppliers extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = ProductSuppliersData::class;

    public function __construct(
        protected readonly string $productId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'product-suppliers';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductID' => $this->productId,
        ]);
    }
}
