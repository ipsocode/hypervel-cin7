<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZoneData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET reference/shipZones` — the list envelope is keyed `ShipZones`.
 *
 * @extends ListRequest<list<ShippingZoneData>>
 */
final class GetShipZones extends ListRequest
{
    protected string $listKey = 'ShipZones';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'reference/shipZones';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Search' => $this->search,
        ];
    }

    /**
     * @return list<ShippingZoneData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ShippingZoneData => ShippingZoneData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
