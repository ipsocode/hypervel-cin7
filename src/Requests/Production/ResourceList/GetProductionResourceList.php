<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ResourceList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Resource\ResourceData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET production/resourceList`, the list envelope is keyed `Resources`.
 *
 * @extends ListRequest<list<ResourceData>>
 */
final class GetProductionResourceList extends ListRequest
{
    protected string $listKey = 'Resources';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
        protected readonly ?bool $onlyActive = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'production/resourceList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Name' => $this->name,
            'OnlyActive' => $this->onlyActive,
        ];
    }

    /**
     * @return list<ResourceData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ResourceData => ResourceData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
