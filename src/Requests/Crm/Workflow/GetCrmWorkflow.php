<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Workflow;

use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/workflow` — the list envelope is keyed `Workflows`.
 *
 * @extends ListRequest<WorkflowData>
 */
final class GetCrmWorkflow extends ListRequest
{
    protected string $listKey = 'Workflows';

    protected string $item = WorkflowData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'crm/workflow';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
        ];
    }
}
