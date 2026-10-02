<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\ProductSuppliers\ProductSuppliersData;
use Ipsocode\Cin7\Requests\ProductSuppliers\DeleteProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\GetProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PostProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PutProductSuppliers;

/**
 * `product-suppliers`, the suppliers of a product. A POST or PUT answers `{Success}`, which is left to
 * `json()`; an empty `ProductSupplierOptions` on a PUT deletes the options.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductSuppliersResource extends BaseResource
{
    /**
     * A product's suppliers.
     */
    public function get(string $productId): Response
    {
        return $this->connector->send(new GetProductSuppliers($productId));
    }

    /**
     * @param array<string, mixed>|ProductSuppliersData $body
     */
    public function post(array|ProductSuppliersData $body): Response
    {
        return $this->connector->send(new PostProductSuppliers($body));
    }

    /**
     * @param array<string, mixed>|ProductSuppliersData $body
     */
    public function put(array|ProductSuppliersData $body): Response
    {
        return $this->connector->send(new PutProductSuppliers($body));
    }

    /**
     * Delete a product's supplier.
     */
    public function delete(string $productId, string $supplierId): Response
    {
        return $this->connector->send(new DeleteProductSuppliers($productId, $supplierId));
    }
}
