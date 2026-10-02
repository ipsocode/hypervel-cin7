<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Unit;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasureData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/unit` — the list envelope is keyed `UnitList`.
 *
 * @extends ListRequest<list<UnitOfMeasureData>>
 */
final class GetUnit extends ListRequest
{
    protected string $listKey = 'UnitList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/unit';
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
     * @return list<UnitOfMeasureData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): UnitOfMeasureData => UnitOfMeasureData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
