<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Lead\LeadPostData;
use Ipsocode\Cin7\Data\Crm\Lead\LeadPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Crm\Lead\GetCrmLead;
use Ipsocode\Cin7\Requests\Crm\Lead\PostCrmLead;
use Ipsocode\Cin7\Requests\Crm\Lead\PutCrmLead;

/**
 * `crm/lead`, the leads.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class LeadResource extends BaseResource
{
    /**
     * One page of leads; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the lead with this ID
     * @param null|string $name only leads whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only records modified since this time
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Response {
        return $this->connector->send(new GetCrmLead(
            $page,
            $limit,
            $id,
            $name,
            $modifiedSince,
        ));
    }

    /**
     * Every page of leads, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the lead with this ID
     * @param null|string $name only leads whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only records modified since this time
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCrmLead(
            null,
            $limit,
            $id,
            $name,
            $modifiedSince,
        ));
    }

    /**
     * @param array<string, mixed>|LeadPostData $body
     */
    public function post(array|LeadPostData $body): Response
    {
        return $this->connector->send(new PostCrmLead($body));
    }

    /**
     * @param array<string, mixed>|LeadPutData $body
     */
    public function put(array|LeadPutData $body): Response
    {
        return $this->connector->send(new PutCrmLead($body));
    }
}
