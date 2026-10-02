<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\ProductAvailability\GetProductAvailability;

/**
 * `ref/productavailability`, the stock of each product by location, bin and batch.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductAvailabilityResource extends BaseResource
{
    /**
     * One page of product availability; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the product with this ID
     * @param null|string $name only products whose name starts with this
     * @param null|string $sku only the product with this SKU
     * @param null|string $location only the stock at this location name
     * @param null|string $batch only the stock of this batch or serial number
     * @param null|string $category only products in this product category name
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        ?string $location = null,
        ?string $batch = null,
        ?string $category = null,
    ): Response {
        return $this->connector->send(new GetProductAvailability($page, $limit, $id, $name, $sku, $location, $batch, $category));
    }

    /**
     * Every page of product availability, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the product with this ID
     * @param null|string $name only products whose name starts with this
     * @param null|string $sku only the product with this SKU
     * @param null|string $location only the stock at this location name
     * @param null|string $batch only the stock of this batch or serial number
     * @param null|string $category only products in this product category name
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        ?string $location = null,
        ?string $batch = null,
        ?string $category = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetProductAvailability(null, $limit, $id, $name, $sku, $location, $batch, $category));
    }
}
