<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Reference;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPostData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Reference\Deals\GetDeals;
use Ipsocode\Cin7\Requests\Reference\Deals\PostDeals;
use Ipsocode\Cin7\Requests\Reference\Deals\PutDeals;

/**
 * `reference/deals`, the product deals. It has no DELETE.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class DealsResource extends BaseResource
{
    /**
     * One page of deals; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the deal with this ID
     * @param null|string $search only deals with this text in their name
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Response {
        return $this->connector->send(new GetDeals($page, $limit, $id, $search));
    }

    /**
     * Every page of deals, fetched as they are walked; call `startPage()` on the paginator to begin
     * later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the deal with this ID
     * @param null|string $search only deals with this text in their name
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetDeals(null, $limit, $id, $search));
    }

    /**
     * @param array<string, mixed>|ProductDealPostData $body
     */
    public function post(array|ProductDealPostData $body): Response
    {
        return $this->connector->send(new PostDeals($body));
    }

    /**
     * @param array<string, mixed>|ProductDealPutData $body
     */
    public function put(array|ProductDealPutData $body): Response
    {
        return $this->connector->send(new PutDeals($body));
    }
}
