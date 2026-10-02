<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\WorkflowStart;

use DateTimeInterface;
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Enums\TaskEntityType;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `POST crm/workflowstart?StartDate&EnityType&EntityID`, starts a workflow, by `ID` or `Name`, on a
 * record. The reference sends everything in the query and nothing in the body, and spells the entity
 * type's key `EnityType`; so does this. The response `{Success}` is left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class PostCrmWorkflowStart extends Cin7Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly DateTimeInterface|string $startDate,
        protected readonly TaskEntityType|string $entityType,
        protected readonly string $entityId,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'crm/workflowstart';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'Name' => $this->name,
            'StartDate' => $this->startDate,
            'EnityType' => $this->entityType,
            'EntityID' => $this->entityId,
        ]);
    }
}
