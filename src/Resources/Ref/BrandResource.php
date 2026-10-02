<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Brand\BrandPostData;
use Ipsocode\Cin7\Data\Ref\Brand\BrandPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Brand\DeleteBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\GetBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\PostBrand;
use Ipsocode\Cin7\Requests\Ref\Brand\PutBrand;

/**
 * `ref/brand`, the brands.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class BrandResource extends BaseResource
{
    /**
     * One page of brands; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only brands whose name starts with this
     */
    public function get(?int $page = null, ?int $limit = null, ?string $name = null): Response
    {
        return $this->connector->send(new GetBrand($page, $limit, $name));
    }

    /**
     * Every page of brands, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $name only brands whose name starts with this
     */
    public function paginate(?int $limit = null, ?string $name = null): Cin7Paginator
    {
        return $this->connector->paginate(new GetBrand(null, $limit, $name));
    }

    /**
     * @param array<string, mixed>|BrandPostData $body
     */
    public function post(array|BrandPostData $body): Response
    {
        return $this->connector->send(new PostBrand($body));
    }

    /**
     * @param array<string, mixed>|BrandPutData $body
     */
    public function put(array|BrandPutData $body): Response
    {
        return $this->connector->send(new PutBrand($body));
    }

    /**
     * Delete the brand with this ID.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteBrand($id));
    }
}
