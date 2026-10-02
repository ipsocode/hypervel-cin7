<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductSuppliers;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE product-suppliers?ProductID&SupplierID`, deletes a product's supplier; the response `{Success}` is left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteProductSuppliers extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $productId,
        protected readonly string $supplierId,
    ) {
        parent::__construct();
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
            'SupplierID' => $this->supplierId,
        ]);
    }
}
