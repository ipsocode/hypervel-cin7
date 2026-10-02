<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Location;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Location\LocationData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/location` — the list envelope is keyed `LocationList`.
 *
 * @extends ListRequest<list<LocationData>>
 */
final class GetLocation extends ListRequest
{
    protected string $listKey = 'LocationList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?bool $deprecated = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/location';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Deprecated' => $this->deprecated,
            'Name' => $this->name,
        ];
    }

    /**
     * @return list<LocationData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): LocationData => LocationData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
