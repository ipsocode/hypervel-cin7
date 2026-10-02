<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowPostData;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Crm\Workflow\GetCrmWorkflow;
use Ipsocode\Cin7\Requests\Crm\Workflow\PostCrmWorkflow;
use Ipsocode\Cin7\Requests\Crm\Workflow\PutCrmWorkflow;

/**
 * `crm/workflow`, the workflows.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class WorkflowResource extends BaseResource
{
    /**
     * One page of workflows; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the workflow with this ID
     * @param null|string $name only workflows whose name starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetCrmWorkflow(
            $page,
            $limit,
            $id,
            $name,
        ));
    }

    /**
     * Every page of workflows, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the workflow with this ID
     * @param null|string $name only workflows whose name starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCrmWorkflow(
            null,
            $limit,
            $id,
            $name,
        ));
    }

    /**
     * @param array<string, mixed>|WorkflowPostData $body
     */
    public function post(array|WorkflowPostData $body): Response
    {
        return $this->connector->send(new PostCrmWorkflow($body));
    }

    /**
     * @param array<string, mixed>|WorkflowPutData $body
     */
    public function put(array|WorkflowPutData $body): Response
    {
        return $this->connector->send(new PutCrmWorkflow($body));
    }
}
