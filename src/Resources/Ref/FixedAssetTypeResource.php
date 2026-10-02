<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypePostData;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypePutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\GetFixedAssetType;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\PostFixedAssetType;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\PutFixedAssetType;

/**
 * `ref/fixedassettype`, the fixed asset types.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class FixedAssetTypeResource extends BaseResource
{
    /**
     * One page of fixed asset types; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $fixedAssetTypeId only the fixed asset type with this ID
     * @param null|string $name only fixed asset types whose name starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $fixedAssetTypeId = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetFixedAssetType($page, $limit, $fixedAssetTypeId, $name));
    }

    /**
     * Every page of fixed asset types, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $fixedAssetTypeId only the fixed asset type with this ID
     * @param null|string $name only fixed asset types whose name starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $fixedAssetTypeId = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetFixedAssetType(null, $limit, $fixedAssetTypeId, $name));
    }

    /**
     * @param array<string, mixed>|FixedAssetTypePostData $body
     */
    public function post(array|FixedAssetTypePostData $body): Response
    {
        return $this->connector->send(new PostFixedAssetType($body));
    }

    /**
     * @param array<string, mixed>|FixedAssetTypePutData $body
     */
    public function put(array|FixedAssetTypePutData $body): Response
    {
        return $this->connector->send(new PutFixedAssetType($body));
    }
}
