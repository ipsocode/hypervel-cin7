<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityPostData;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Crm\Opportunity\GetCrmOpportunity;
use Ipsocode\Cin7\Requests\Crm\Opportunity\PostCrmOpportunity;
use Ipsocode\Cin7\Requests\Crm\Opportunity\PutCrmOpportunity;

/**
 * `crm/opportunity`, the opportunities.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OpportunityResource extends BaseResource
{
    /**
     * One page of opportunities; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the opportunity with this ID
     * @param null|DateTimeInterface|string $modifiedSince only records modified since this time
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Response {
        return $this->connector->send(new GetCrmOpportunity(
            $page,
            $limit,
            $id,
            $modifiedSince,
        ));
    }

    /**
     * Every page of opportunities, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the opportunity with this ID
     * @param null|DateTimeInterface|string $modifiedSince only records modified since this time
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCrmOpportunity(
            null,
            $limit,
            $id,
            $modifiedSince,
        ));
    }

    /**
     * @param array<string, mixed>|OpportunityPostData $body
     */
    public function post(array|OpportunityPostData $body): Response
    {
        return $this->connector->send(new PostCrmOpportunity($body));
    }

    /**
     * @param array<string, mixed>|OpportunityPutData $body
     */
    public function put(array|OpportunityPutData $body): Response
    {
        return $this->connector->send(new PutCrmOpportunity($body));
    }
}
