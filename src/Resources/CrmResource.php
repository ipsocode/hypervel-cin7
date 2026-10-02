<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\Crm\LeadResource;
use Ipsocode\Cin7\Resources\Crm\OpportunityResource;
use Ipsocode\Cin7\Resources\Crm\TaskCategoryResource;
use Ipsocode\Cin7\Resources\Crm\TaskResource;
use Ipsocode\Cin7\Resources\Crm\WorkflowResource;
use Ipsocode\Cin7\Resources\Crm\WorkflowStartResource;

/**
 * Groups the `crm/…` resources; V2 has no action on `/crm` itself.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CrmResource extends BaseResource
{
    /**
     * The `crm/lead` resource.
     */
    public function lead(): LeadResource
    {
        return new LeadResource($this->connector);
    }

    /**
     * The `crm/opportunity` resource.
     */
    public function opportunity(): OpportunityResource
    {
        return new OpportunityResource($this->connector);
    }

    /**
     * The `crm/task` resource.
     */
    public function task(): TaskResource
    {
        return new TaskResource($this->connector);
    }

    /**
     * The `crm/taskcategory` resource.
     */
    public function taskCategory(): TaskCategoryResource
    {
        return new TaskCategoryResource($this->connector);
    }

    /**
     * The `crm/workflow` resource.
     */
    public function workflow(): WorkflowResource
    {
        return new WorkflowResource($this->connector);
    }

    /**
     * The `crm/workflowstart` resource.
     */
    public function workflowStart(): WorkflowStartResource
    {
        return new WorkflowStartResource($this->connector);
    }
}
