<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCenterData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET production/workcenters`, the list envelope is keyed `Workcenters`.
 *
 * @extends ListRequest<list<WorkCenterData>>
 */
final class GetProductionWorkCenters extends ListRequest
{
    protected string $listKey = 'Workcenters';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Name' => $this->name,
        ];
    }

    /**
     * @return list<WorkCenterData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): WorkCenterData => WorkCenterData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
