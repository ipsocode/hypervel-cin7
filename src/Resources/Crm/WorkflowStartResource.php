<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Requests\Crm\WorkflowStart\PostCrmWorkflowStart;

/**
 * `crm/workflowstart`, starting a workflow on a record.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class WorkflowStartResource extends BaseResource
{
    /**
     * Start a workflow, named by `$id` or `$name`, on a record.
     *
     * @param DateTimeInterface|string $startDate when the workflow starts
     * @param string $entityId the ID of the record
     * @param null|string $id the ID of the workflow
     * @param null|string $name the name of the workflow
     */
    public function post(
        DateTimeInterface|string $startDate,
        TaskEntityType|string $entityType,
        string $entityId,
        ?string $id = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new PostCrmWorkflowStart($startDate, $entityType, $entityId, $id, $name));
    }
}
