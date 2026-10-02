<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryPostData;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Category\DeleteCategory;
use Ipsocode\Cin7\Requests\Ref\Category\GetCategory;
use Ipsocode\Cin7\Requests\Ref\Category\PostCategory;
use Ipsocode\Cin7\Requests\Ref\Category\PutCategory;

/**
 * `ref/category`, the product categories.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CategoryResource extends BaseResource
{
    /**
     * One page of product categories; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only product categories whose name starts with this
     */
    public function get(?int $page = null, ?int $limit = null, ?string $name = null): Response
    {
        return $this->connector->send(new GetCategory($page, $limit, $name));
    }

    /**
     * Every page of product categories, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only product categories whose name starts with this
     */
    public function paginate(?int $limit = null, ?string $name = null): Cin7Paginator
    {
        return $this->connector->paginate(new GetCategory(null, $limit, $name));
    }

    /**
     * @param array<string, mixed>|ProductCategoryPostData $body
     */
    public function post(array|ProductCategoryPostData $body): Response
    {
        return $this->connector->send(new PostCategory($body));
    }

    /**
     * @param array<string, mixed>|ProductCategoryPutData $body
     */
    public function put(array|ProductCategoryPutData $body): Response
    {
        return $this->connector->send(new PutCategory($body));
    }

    /**
     * Delete the product category with this ID.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteCategory($id));
    }
}
