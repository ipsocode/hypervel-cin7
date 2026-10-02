<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\ProductPostData;
use Ipsocode\Cin7\Data\Product\ProductPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Ipsocode\Cin7\Resources\Product\MarkupPricesResource;

/**
 * `product`; `markupPrices()` is the `product/markupprices` sub-resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductResource extends BaseResource
{
    /**
     * One page of products; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the product with this ID
     * @param null|string $name only products whose name contains this
     * @param null|string $sku only products whose SKU contains this
     * @param null|DateTimeInterface|string $modifiedSince only products modified since this time
     * @param null|bool $includeDeprecated include deprecated products too
     * @param null|bool $includeBom include the bill of materials
     * @param null|bool $includeSuppliers include the suppliers
     * @param null|bool $includeMovements include the movements
     * @param null|bool $includeAttachments include the attachments
     * @param null|bool $includeReorderLevels include the reorder levels
     * @param null|bool $includeCustomPrices include the customer specific prices
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
        ?bool $includeBom = null,
        ?bool $includeSuppliers = null,
        ?bool $includeMovements = null,
        ?bool $includeAttachments = null,
        ?bool $includeReorderLevels = null,
        ?bool $includeCustomPrices = null,
    ): Response {
        return $this->connector->send(new GetProduct(
            $page,
            $limit,
            $id,
            $name,
            $sku,
            $modifiedSince,
            $includeDeprecated,
            $includeBom,
            $includeSuppliers,
            $includeMovements,
            $includeAttachments,
            $includeReorderLevels,
            $includeCustomPrices,
        ));
    }

    /**
     * Every page of products, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the product with this ID
     * @param null|string $name only products whose name contains this
     * @param null|string $sku only products whose SKU contains this
     * @param null|DateTimeInterface|string $modifiedSince only products modified since this time
     * @param null|bool $includeDeprecated include deprecated products too
     * @param null|bool $includeBom include the bill of materials
     * @param null|bool $includeSuppliers include the suppliers
     * @param null|bool $includeMovements include the movements
     * @param null|bool $includeAttachments include the attachments
     * @param null|bool $includeReorderLevels include the reorder levels
     * @param null|bool $includeCustomPrices include the customer specific prices
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
        ?bool $includeBom = null,
        ?bool $includeSuppliers = null,
        ?bool $includeMovements = null,
        ?bool $includeAttachments = null,
        ?bool $includeReorderLevels = null,
        ?bool $includeCustomPrices = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetProduct(
            null,
            $limit,
            $id,
            $name,
            $sku,
            $modifiedSince,
            $includeDeprecated,
            $includeBom,
            $includeSuppliers,
            $includeMovements,
            $includeAttachments,
            $includeReorderLevels,
            $includeCustomPrices,
        ));
    }

    /**
     * @param array<string, mixed>|ProductPostData $body
     */
    public function post(array|ProductPostData $body): Response
    {
        return $this->connector->send(new PostProduct($body));
    }

    /**
     * @param array<string, mixed>|ProductPutData $body
     */
    public function put(array|ProductPutData $body): Response
    {
        return $this->connector->send(new PutProduct($body));
    }

    /**
     * The `product/markupprices` resource, a product's markup prices.
     */
    public function markupPrices(): MarkupPricesResource
    {
        return new MarkupPricesResource($this->connector);
    }
}
