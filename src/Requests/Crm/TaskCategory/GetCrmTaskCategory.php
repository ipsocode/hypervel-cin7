<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\TaskCategory;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/taskcategory` — the list envelope is keyed `Categories`.
 *
 * @extends ListRequest<list<TaskCategoryData>>
 */
final class GetCrmTaskCategory extends ListRequest
{
    protected string $listKey = 'Categories';

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
        return 'crm/taskcategory';
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

    /**
     * @return list<TaskCategoryData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TaskCategoryData => TaskCategoryData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
