<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyPostData;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\ProductFamily\GetProductFamily;
use Ipsocode\Cin7\Requests\ProductFamily\PostProductFamily;
use Ipsocode\Cin7\Requests\ProductFamily\PutProductFamily;
use Ipsocode\Cin7\Resources\ProductFamily\AttachmentsResource;

/**
 * `productFamily`; `attachments()` is its `productFamily/attachments` sub-resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductFamilyResource extends BaseResource
{
    /**
     * One page of product families; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the family with this ID
     * @param null|string $name only families whose name contains this
     * @param null|string $sku only families whose SKU contains this
     * @param null|DateTimeInterface|string $modifiedSince only families changed since this time (UTC)
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Response {
        return $this->connector->send(new GetProductFamily($page, $limit, $id, $name, $sku, $modifiedSince));
    }

    /**
     * Every page of product families, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the family with this ID
     * @param null|string $name only families whose name contains this
     * @param null|string $sku only families whose SKU contains this
     * @param null|DateTimeInterface|string $modifiedSince only families changed since this time (UTC)
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?string $sku = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetProductFamily(null, $limit, $id, $name, $sku, $modifiedSince));
    }

    /**
     * @param array<string, mixed>|ProductFamilyPostData $body
     */
    public function post(array|ProductFamilyPostData $body): Response
    {
        return $this->connector->send(new PostProductFamily($body));
    }

    /**
     * Change a family; the products it lists are added or updated, and none is ever deleted.
     *
     * @param array<string, mixed>|ProductFamilyPutData $body
     */
    public function put(array|ProductFamilyPutData $body): Response
    {
        return $this->connector->send(new PutProductFamily($body));
    }

    /**
     * The `productFamily/attachments` resource, a family's attachments.
     */
    public function attachments(): AttachmentsResource
    {
        return new AttachmentsResource($this->connector);
    }
}
