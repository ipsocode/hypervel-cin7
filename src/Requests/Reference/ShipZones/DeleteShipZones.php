<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\ShipZones;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE reference/shipZones?ShipZoneID`, deletes a ship zone; the response `{Success}` is left to
 * `json()`. The reference documents the key as `ShipZoneID ` with a trailing space: it is sent
 * without it.
 *
 * @extends Cin7Request<null>
 */
final class DeleteShipZones extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $shipZoneId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'reference/shipZones';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ShipZoneID' => $this->shipZoneId,
        ]);
    }
}
