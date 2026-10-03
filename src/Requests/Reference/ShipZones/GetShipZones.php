<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZoneData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET reference/shipZones` — the list envelope is keyed `ShipZones`.
 *
 * @extends ListRequest<ShippingZoneData>
 */
final class GetShipZones extends ListRequest
{
    protected string $listKey = 'ShipZones';

    protected string $item = ShippingZoneData::class;

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
}
