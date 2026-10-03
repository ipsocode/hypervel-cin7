<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Reference;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulePutData;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRulesPostData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Reference\Discount\GetDiscount;
use Ipsocode\Cin7\Requests\Reference\Discount\PostDiscount;
use Ipsocode\Cin7\Requests\Reference\Discount\PutDiscount;

/**
 * `reference/discount`, the product discount rules. It has no DELETE.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class DiscountResource extends BaseResource
{
    /**
     * One page of discount rules; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the discount rule with this ID
     * @param null|string $search only discount rules with this text in their name
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Response {
        return $this->connector->send(new GetDiscount($page, $limit, $id, $search));
    }

    /**
     * Every page of discount rules, fetched as they are walked; call `startPage()` on the paginator
     * to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the discount rule with this ID
     * @param null|string $search only discount rules with this text in their name
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetDiscount(null, $limit, $id, $search));
    }

    /**
     * Create discount rules: the body lists them under `DiscountRules`.
     *
     * @param array<string, mixed>|ProductDiscountRulesPostData $body
     */
    public function post(array|ProductDiscountRulesPostData $body): Response
    {
        return $this->connector->send(new PostDiscount($body));
    }

    /**
     * Change one discount rule: the body is the rule itself.
     *
     * @param array<string, mixed>|ProductDiscountRulePutData $body
     */
    public function put(array|ProductDiscountRulePutData $body): Response
    {
        return $this->connector->send(new PutDiscount($body));
    }
}
