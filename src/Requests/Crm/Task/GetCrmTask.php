<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Task;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Task\TaskData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/task` — the list envelope is keyed `Tasks`.
 *
 * @extends ListRequest<list<TaskData>>
 */
final class GetCrmTask extends ListRequest
{
    protected string $listKey = 'Tasks';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly DateTimeInterface|string|null $startDateFrom = null,
        protected readonly DateTimeInterface|string|null $startDateTo = null,
        protected readonly DateTimeInterface|string|null $endDateFrom = null,
        protected readonly DateTimeInterface|string|null $endDateTo = null,
        protected readonly DateTimeInterface|string|null $completeDateFrom = null,
        protected readonly DateTimeInterface|string|null $completeDateTo = null,
        protected readonly ?string $assignedTo = null,
        protected readonly ?string $category = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'crm/task';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'StartDateFrom' => $this->startDateFrom,
            'StartDateTo' => $this->startDateTo,
            'EndDateFrom' => $this->endDateFrom,
            'EndDateTo' => $this->endDateTo,
            'CompleteDateFrom' => $this->completeDateFrom,
            'CompleteDateTo' => $this->completeDateTo,
            'AssignedTo' => $this->assignedTo,
            'Category' => $this->category,
        ];
    }

    /**
     * @return list<TaskData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TaskData => TaskData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
