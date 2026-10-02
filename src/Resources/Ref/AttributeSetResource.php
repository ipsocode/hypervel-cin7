<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetPostData;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\DeleteAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\GetAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\PostAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\PutAttributeSet;

/**
 * `ref/attributeset`, the attribute sets.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AttributeSetResource extends BaseResource
{
    /**
     * One page of attribute sets; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the attribute set with this ID
     * @param null|string $name only attribute sets whose name starts with this
     */
    public function get(?int $page = null, ?int $limit = null, ?string $id = null, ?string $name = null): Response
    {
        return $this->connector->send(new GetAttributeSet($page, $limit, $id, $name));
    }

    /**
     * Every page of attribute sets, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the attribute set with this ID
     * @param null|string $name only attribute sets whose name starts with this
     */
    public function paginate(?int $limit = null, ?string $id = null, ?string $name = null): Cin7Paginator
    {
        return $this->connector->paginate(new GetAttributeSet(null, $limit, $id, $name));
    }

    /**
     * @param array<string, mixed>|AttributeSetPostData $body
     */
    public function post(array|AttributeSetPostData $body): Response
    {
        return $this->connector->send(new PostAttributeSet($body));
    }

    /**
     * @param array<string, mixed>|AttributeSetPutData $body
     */
    public function put(array|AttributeSetPutData $body): Response
    {
        return $this->connector->send(new PutAttributeSet($body));
    }

    /**
     * Delete the attribute set with this ID.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteAttributeSet($id));
    }
}
